<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DonationImpact extends Model
{
    protected $fillable = [
        'min_amount',
        'max_amount',
        'title',
        'message',
        'sort_order',
        'is_active',
    ];
}
