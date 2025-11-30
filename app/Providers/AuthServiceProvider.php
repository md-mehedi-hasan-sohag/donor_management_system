<?php

namespace App\Providers;

use App\Models\Campaign;
use App\Models\Donation;
use App\Models\CampaignComment;
use App\Policies\CampaignPolicy;
use App\Policies\DonationPolicy;
use App\Policies\CampaignCommentPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Campaign::class => CampaignPolicy::class,
        Donation::class => DonationPolicy::class,
        CampaignComment::class => CampaignCommentPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}