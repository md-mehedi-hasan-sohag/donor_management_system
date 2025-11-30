<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public function index(Request $request)
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
        $sort = $request->get('sort', 'latest');
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

        $campaigns = $query->paginate(12);
        $categories = Category::all();

        return view('campaigns.index', compact('campaigns', 'categories'));
    }

    public function show(Campaign $campaign)
    {
        $campaign->load('user', 'category', 'updates', 'comments.user', 'donations');

        $similarCampaigns = Campaign::active()
            ->where('category_id', $campaign->category_id)
            ->where('id', '!=', $campaign->id)
            ->take(3)
            ->get();

        return view('campaigns.show', compact('campaign', 'similarCampaigns'));
    }

    public function create()
    {
        $user = auth()->user();

        // Debug logging
        \Log::info('Campaign create accessed', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'verification_status' => $user->verification_status,
            'isRecipient' => $user->isRecipient(),
            'isVerified' => $user->isVerified(),
        ]);

        // Check if user is a recipient
        if (!$user->isRecipient()) {
            \Log::warning('Non-recipient tried to create campaign', ['user_id' => $user->id, 'role' => $user->role]);
            return redirect()->route('dashboard')
                ->with('error', 'Only verified recipients can create campaigns. Please register as a recipient first.');
        }

        // Check if user is verified
        if (!$user->isVerified()) {
            \Log::warning('Unverified recipient tried to create campaign', ['user_id' => $user->id]);
            return redirect()->route('verification.index')
                ->with('error', 'You must complete verification before creating campaigns.');
        }

        $categories = Category::all();
        \Log::info('Showing campaign create form', ['user_id' => $user->id, 'categories_count' => $categories->count()]);
        return view('campaigns.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Check if user is a recipient
        if (!$user->isRecipient()) {
            return redirect()->route('dashboard')
                ->with('error', 'Only verified recipients can create campaigns.');
        }

        // Check if user is verified
        if (!$user->isVerified()) {
            return redirect()->route('verification.index')
                ->with('error', 'You must complete verification before creating campaigns.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'goal_amount' => 'required|numeric|min:100',
            'end_date' => 'required|date|after:today',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'is_urgent' => 'boolean',
            'accepts_volunteers' => 'boolean',
            'accepts_in_kind' => 'boolean',
            'in_kind_needs' => 'nullable|string',
        ]);

        $campaign = new Campaign($validated);
        $campaign->user_id = $user->id;
        $campaign->slug = Str::slug($request->title) . '-' . Str::random(6);
        $campaign->status = 'pending';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('campaigns', 'public');
            $campaign->image_path = $path;
        }

        $campaign->save();

        return redirect()->route('dashboard')
            ->with('success', 'Campaign submitted for approval!');
    }

    public function edit(Campaign $campaign)
    {
        $this->authorize('update', $campaign);

        $categories = Category::all();
        return view('campaigns.edit', compact('campaign', 'categories'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $this->authorize('update', $campaign);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'goal_amount' => 'required|numeric|min:100',
            'end_date' => 'required|date|after:today',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
            'is_urgent' => 'boolean',
            'accepts_volunteers' => 'boolean',
            'accepts_in_kind' => 'boolean',
            'in_kind_needs' => 'nullable|string',
        ]);

        $campaign->fill($validated);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('campaigns', 'public');
            $campaign->image_path = $path;
        }

        $campaign->save();

        return redirect()->route('campaigns.show', $campaign)
            ->with('success', 'Campaign updated successfully!');
    }

    public function follow(Campaign $campaign)
    {
        $user = auth()->user();

        if ($user->followedCampaigns()->where('campaign_id', $campaign->id)->exists()) {
            $user->followedCampaigns()->detach($campaign->id);
            $campaign->decrement('followers_count');
            $message = 'Campaign unfollowed.';
        } else {
            $user->followedCampaigns()->attach($campaign->id);
            $campaign->increment('followers_count');
            $message = 'Campaign followed!';
        }

        return back()->with('success', $message);
    }
}