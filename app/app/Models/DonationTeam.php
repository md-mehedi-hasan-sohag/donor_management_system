<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'created_by',
        'name',
        'slug',
        'description',
        'team_goal',
        'total_raised',
        'team_image',
    ];

    protected $casts = [
        'team_goal' => 'decimal:2',
        'total_raised' => 'decimal:2',
    ];

    // Relationships
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function members()
    {
    return $this->belongsToMany(User::class, 'team_members', 'team_id', 'user_id')
        ->withPivot('role')
        ->withTimestamps();
    }

    public function donations()
    {
        return $this->hasMany(Donation::class, 'team_id');
    }

    // Helpers
    public function progressPercentage()
    {
        if (!$this->team_goal || $this->team_goal == 0) return 0;
        return min(($this->total_raised / $this->team_goal) * 100, 100);
    }

    public function membersCount()
    {
        return $this->members()->count();
    }
}