<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CampaignService
{
    /**
     * Get filtered and paginated campaigns
     */
    public function getCampaigns(Request $request)
    {
        $query = Campaign::with('category', 'user')->active();

        // Search
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->byLocation($request->location);
        }

        // Filter by urgency
        if ($request->filled('urgent')) {
            $query->urgent();
        }

        // Filter by verification
        if ($request->filled('verified')) {
            $query->verified();
        }

        // Sort
        $this->applySorting($query, $request->get('sort', 'latest'));

        return $query->paginate(12);
    }

    /**
     * Apply sorting to campaign query
     */
    private function applySorting($query, string $sort)
    {
        switch ($sort) {
            case 'ending_soon':
                $query->orderBy('end_date', 'asc');
                break;
            case 'most_funded':
                $query->orderBy('current_amount', 'desc');
                break;
            default:
                $query->latest();
        }
    }

    /**
     * Get similar campaigns
     */
    public function getSimilarCampaigns(Campaign $campaign, int $limit = 3)
    {
        return Campaign::active()
            ->where('category_id', $campaign->category_id)
            ->where('id', '!=', $campaign->id)
            ->take($limit)
            ->get();
    }

    /**
     * Create a new campaign
     */
    public function createCampaign(array $data, User $user, $imageFile = null): Campaign
    {
        $campaign = new Campaign($data);
        $campaign->user_id = $user->id;
        $campaign->slug = $this->generateUniqueSlug($data['title']);
        $campaign->status = 'pending';

        if ($imageFile) {
            $campaign->image_path = $this->uploadImage($imageFile);
        }

        $campaign->save();

        return $campaign;
    }

    /**
     * Update an existing campaign
     */
    public function updateCampaign(Campaign $campaign, array $data, $imageFile = null): Campaign
    {
        $campaign->fill($data);

        if ($imageFile) {
            // Delete old image if exists
            if ($campaign->image_path) {
                Storage::disk('public')->delete($campaign->image_path);
            }
            $campaign->image_path = $this->uploadImage($imageFile);
        }

        $campaign->save();

        return $campaign;
    }

    /**
     * Toggle campaign follow status for a user
     */
    public function toggleFollow(Campaign $campaign, User $user): array
    {
        if ($user->followedCampaigns()->where('campaign_id', $campaign->id)->exists()) {
            $user->followedCampaigns()->detach($campaign->id);
            $campaign->decrement('followers_count');
            return ['followed' => false, 'message' => 'Campaign unfollowed.'];
        } else {
            $user->followedCampaigns()->attach($campaign->id);
            $campaign->increment('followers_count');
            return ['followed' => true, 'message' => 'Campaign followed!'];
        }
    }

    /**
     * Generate a unique slug for campaign
     */
    private function generateUniqueSlug(string $title): string
    {
        return Str::slug($title) . '-' . Str::random(6);
    }

    /**
     * Upload campaign image
     */
    private function uploadImage($file): string
    {
        return $file->store('campaigns', 'public');
    }

    /**
     * Validate if user can create campaign
     */
    public function canUserCreateCampaign(User $user): array
    {
        if (!$user->isRecipient()) {
            return [
                'can_create' => false,
                'message' => 'Only verified recipients can create campaigns. Please register as a recipient first.',
                'redirect' => 'dashboard'
            ];
        }

        if (!$user->isVerified()) {
            return [
                'can_create' => false,
                'message' => 'You must complete verification before creating campaigns.',
                'redirect' => 'verification.index'
            ];
        }

        return ['can_create' => true];
    }
}
