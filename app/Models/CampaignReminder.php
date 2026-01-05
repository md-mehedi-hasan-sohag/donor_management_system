<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'recipient_id',
        'message',
    ];

    // Relationship with Campaign (optional, if needed)
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    // Relationship with User (optional, if needed)
    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
