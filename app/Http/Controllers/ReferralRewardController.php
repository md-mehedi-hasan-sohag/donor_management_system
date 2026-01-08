<?php

namespace App\Http\Controllers;

use App\Models\Referral;

class ReferralRewardController extends Controller
{
    public static function handleSuccessfulDonation(int $donorUserId): void
    {
        $referral = Referral::where('referred_user_id', $donorUserId)
            ->where('status', 'signed_up') // ensures only once
            ->first();

        if ($referral) {
            $referral->update([
                'status' => 'donated',
                'reward_given' => true,
            ]);
        }
    }
}
