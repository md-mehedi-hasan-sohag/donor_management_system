<?php

namespace App\Policies;

use App\Models\Campaign;
use App\Models\User;

class CampaignPolicy
{
    public function viewAny(?User $user)
    {
        return true;
    }

    public function view(?User $user, Campaign $campaign)
    {
        return $campaign->status === 'active' || 
               ($user && ($user->id === $campaign->user_id || $user->isAdmin()));
    }

    public function create(User $user)
    {
        return $user->isRecipient() && $user->isVerified();
    }

    public function update(User $user, Campaign $campaign)
    {
        return $user->id === $campaign->user_id || $user->isAdmin();
    }

    public function delete(User $user, Campaign $campaign)
    {
        return $user->id === $campaign->user_id || $user->isAdmin();
    }
}