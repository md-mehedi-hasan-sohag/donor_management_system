<?php

namespace App\Http\Controllers;

use App\Helpers\QrCodeHelper;
use App\Models\Campaign;
use App\Models\Category;
use App\Services\CampaignService;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    protected $campaignService;

    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    public function index(Request $request)
    {
        $campaigns = $this->campaignService->getCampaigns($request);
        $categories = Category::all();

        return view('campaigns.index', compact('campaigns', 'categories'));
    }

    public function show(Campaign $campaign)
    {
        $campaign->load('user', 'category', 'updates', 'comments.user', 'donations', 'questions.user');
        $similarCampaigns = $this->campaignService->getSimilarCampaigns($campaign);

        $campaign->increment('views');

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

        // Validate user can create campaign
        $validation = $this->campaignService->canUserCreateCampaign($user);

        if (!$validation['can_create']) {
            return redirect()->route($validation['redirect'])
                ->with('error', $validation['message']);
        }

        $categories = Category::all();
        \Log::info('Showing campaign create form', ['user_id' => $user->id, 'categories_count' => $categories->count()]);
        return view('campaigns.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();

        // Validate user can create campaign
        $validation = $this->campaignService->canUserCreateCampaign($user);

        if (!$validation['can_create']) {
            return redirect()->route($validation['redirect'])
                ->with('error', $validation['message']);
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

        $campaign = $this->campaignService->createCampaign(
            $validated,
            $user,
            $request->file('image')
        );

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

        $campaign = $this->campaignService->updateCampaign(
            $campaign,
            $validated,
            $request->file('image')
        );

        return redirect()->route('campaigns.show', $campaign)
            ->with('success', 'Campaign updated successfully!');
    }

    public function follow(Campaign $campaign)
    {
        $user = auth()->user();
        $result = $this->campaignService->toggleFollow($campaign, $user);

        return back()->with('success', $result['message']);
    }

    /**
     * Download QR code for campaign
     */
    public function downloadQr(Campaign $campaign)
    {
        $qrCode = QrCodeHelper::generateCampaignQr($campaign, 500);

        $filename = 'campaign-' . $campaign->id . '-qr-code.svg';

        return response($qrCode)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }



}