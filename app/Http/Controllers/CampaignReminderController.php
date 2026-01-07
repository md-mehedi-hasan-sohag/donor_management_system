<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignReminderController extends Controller
{
    public function store(Campaign $campaign)
    {
        // Security check: only campaign owner can set reminder
        if ($campaign->recipient_id !== Auth::id()) {
            abort(403);
        }

        CampaignReminder::create([
            'campaign_id' => $campaign->id,
            'recipient_id' => Auth::id(),
            'message' => 'This is your reminder message.',
        ]);

        return redirect()->back()->with('success', 'Reminder has been set successfully.');
    }
}


