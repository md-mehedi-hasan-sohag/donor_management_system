<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'description',
        'image_path',
        'video_path',
        'goal_amount',
        'current_amount',
        'end_date',
        'location',
        'is_urgent',
        'is_verified',
        'accepts_volunteers',
        'accepts_in_kind',
        'in_kind_needs',
        'status',
        'rejection_reason',
        'approved_by',
        'approved_at',
        'total_donors',
        'followers_count',
    ];

    protected $casts = [
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'is_urgent' => 'boolean',
        'is_verified' => 'boolean',
        'accepts_volunteers' => 'boolean',
        'accepts_in_kind' => 'boolean',
        'goal_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function updates()
    {
        return $this->hasMany(CampaignUpdate::class)->orderBy('created_at', 'desc');
    }

    public function expenditureReports()
    {
        return $this->hasMany(ExpenditureReport::class);
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'campaign_followers')
            ->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(CampaignComment::class)->whereNull('parent_id');
    }

    public function fraudReports()
    {
        return $this->hasMany(FraudReport::class);
    }

    public function teams()
    {
        return $this->hasMany(DonationTeam::class);
    }

    public function volunteers()
    {
        return $this->hasMany(VolunteerSignup::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('end_date', '>', now());
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'active')
            ->where('end_date', '<=', now());
    }

    public function scopeUrgent($query)
    {
        return $query->where('is_urgent', true);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByLocation($query, $location)
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    // Helpers
    public function progressPercentage()
    {
        if ($this->goal_amount == 0) return 0;
        return min(($this->current_amount / $this->goal_amount) * 100, 100);
    }

    public function daysRemaining()
    {
        return max(0, now()->diffInDays($this->end_date, false));
    }

    public function isExpired()
    {
        return $this->end_date < now();
    }

    public function isFullyFunded()
    {
        return $this->current_amount >= $this->goal_amount;
    }

    public function hasReachedMilestone($percentage)
    {
        return $this->progressPercentage() >= $percentage;
    }
}