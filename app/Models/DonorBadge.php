<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonorBadge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'criteria_type',
        'criteria_value',
    ];

    protected $casts = [
        'criteria_value' => 'integer',
    ];

    // Relationships vvvv
    public function users()
    {
    return $this->belongsToMany(User::class, 'user_badges', 'badge_id', 'user_id')
        ->withTimestamps();
    }
}