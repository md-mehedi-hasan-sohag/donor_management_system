<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'campaign_id',
        'subject',
        'body',
        'is_read',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
