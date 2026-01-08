<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventTicketDonation extends Model
{
    protected $fillable = [
        'event_id', 'campaign_id', 'user_id', 'donation_id',
        'amount', 'ticket_code', 'purchased_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'purchased_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
