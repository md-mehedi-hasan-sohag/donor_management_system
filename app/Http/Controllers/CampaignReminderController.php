<?php

namespace App\Http\Controllers;

use App\Models\CampaignReminder;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class CampaignReminderController extends Controller
{
    // Store the reminder
    public function store(Request $request, $campaignId)
    {
        // Get the authenticated user
        $user = Auth::user();

        // Retrieve the campaign
        $campaign = Campaign::findOrFail($campaignId);

        // Ensure the user is the recipient of the campaign
        if ($user->id !== $campaign->recipient_id) {
            return redirect()->route('campaigns.show', $campaign)->withErrors('You are not the recipient of this campaign!');
        }

        // Store the reminder message in the database
        CampaignReminder::create([
            'campaign_id' => $campaign->id,
            'recipient_id' => $user->id,
            'message' => 'This is your reminder message.' // Static message
        ]);

        return redirect()->route('campaigns.show', $campaign)->with('success', 'Reminder set successfully!');
    }
}
