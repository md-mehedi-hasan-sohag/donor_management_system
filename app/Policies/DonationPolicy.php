<?php

namespace App\Policies;

use App\Models\Donation;
use App\Models\User;

class DonationPolicy
{
    public function view(User $user, Donation $donation)
    {
        return $user->id === $donation->user_id || 
               $user->id === $donation->campaign->user_id || 
               $user->isAdmin();
    }
}