<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignReminder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignReminderController extends Controller
{
    /**Store a static reminder message for a campaign (recipient/owner only).*/
  public function store(Request $request, Campaign $campaign){$user = Auth::user();

        // ✅ IMPORTANT: In your project, campaign owner is usually user_id (not recipient_id)
        if ($user->id !== $campaign->user_id) {
            return redirect()
                ->route('campaigns.show', $campaign)
                ->with('error', 'You are not allowed to set a reminder for this campaign.');
        }

        // ✅ Optional: Prevent duplicate reminder (one reminder per campaign per user)
        #$alreadyExists = CampaignReminder::where('campaign_id', $campaign->id)
        #    ->where('recipient_id', $user->id)
        #   ->exists();

        #if ($alreadyExists) {
         #   return redirect()
          #      ->route('campaigns.show', $campaign)
           #     ->with('info', 'Reminder already exists for this campaign.');
        #}

        //  Store reminder (static message)
        CampaignReminder::create([
            'campaign_id'  => $campaign->id,
            'recipient_id' => $user->id,
            'message'      => 'This is your reminder message.',
        ]);

        return redirect()
            ->route('campaigns.show', $campaign)
            ->with('success', 'Reminder set successfully!');
    }
}
