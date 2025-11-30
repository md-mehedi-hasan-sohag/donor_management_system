<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CampaignFollower extends Pivot
{
    protected $table = 'campaign_followers';
    
    public $incrementing = true;
}