<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'campaign_id',
        'ticket_price', 'is_active', 'starts_at', 'ends_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'ticket_price' => 'decimal:2',
    ];

    public function getRouteKeyName()
    {
        return 'slug'; // ✅ slug binding
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}
