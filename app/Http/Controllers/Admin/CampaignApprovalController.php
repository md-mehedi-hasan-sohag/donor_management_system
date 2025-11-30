<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;

class CampaignApprovalController extends Controller
{
    public function index()
    {
        $pendingCampaigns = Campaign::pending()
            ->with('user', 'category')
            ->latest()
            ->get();

        return view('admin.campaigns.pending', compact('pendingCampaigns'));
    }

    public function approve(Campaign $campaign)
    {
        $campaign->update([
            'status' => 'active',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // Send notification to campaign creator
        // Mail::to($campaign->user)->send(new CampaignApproved($campaign));

        return back()->with('success', 'Campaign approved successfully!');
    }

    public function reject(Request $request, Campaign $campaign)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $campaign->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Send notification to campaign creator
        // Mail::to($campaign->user)->send(new CampaignRejected($campaign));

        return back()->with('success', 'Campaign rejected.');
    }

    public function verify(Campaign $campaign)
    {
        $campaign->update(['is_verified' => true]);

        return back()->with('success', 'Campaign verified!');
    }
}