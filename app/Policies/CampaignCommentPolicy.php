<?php

namespace App\Policies;

use App\Models\CampaignComment;
use App\Models\User;

class CampaignCommentPolicy
{
    public function delete(User $user, CampaignComment $comment)
    {
        return $user->id === $comment->user_id || 
               $user->id === $comment->campaign->user_id || 
               $user->isAdmin();
    }
}