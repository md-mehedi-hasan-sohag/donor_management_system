<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use Illuminate\Http\Request;

class SavedCampaignController extends Controller
{
    public function index()
    {
        $campaigns = auth()
            ->user()
            ->savedCampaigns()
            ->latest()
            ->paginate(9);

        return view('saved-campaigns.index', compact('campaigns'));
    }

    public function store(Campaign $campaign)
    {
        auth()->user()
            ->savedCampaigns()
            ->syncWithoutDetaching($campaign->id);

        return back()->with('success', 'Campaign saved successfully.');
    }

    public function destroy(Campaign $campaign)
    {
        auth()->user()
            ->savedCampaigns()
            ->detach($campaign->id);

        return back()->with('success', 'Campaign removed from saved list.');
    }
}
