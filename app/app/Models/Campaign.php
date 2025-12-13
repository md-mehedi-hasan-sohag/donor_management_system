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
        'completed_at',
        'archived_at',
        'days_until_archive',
        'total_donors',
        'followers_count',
    ];

    protected $casts = [
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
        'archived_at' => 'datetime',
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

    public function questions()
    {
        return $this->hasMany(CampaignQuestion::class)->orderBy('is_pinned', 'desc')->orderBy('created_at', 'desc');
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

    public function scopeCompleted($query)
    {
        return $query->where('status', 'expired');
    }

    public function scopeExpiredStatus($query)
    {
        return $query->where('status', 'expired');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeNotArchived($query)
    {
        return $query->whereNull('archived_at');
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

    public function isCompleted()
    {
        return $this->status === 'expired' || $this->status === 'archived';
    }

    public function isExpiredStatus()
    {
        return $this->status === 'expired';
    }

    public function isArchived()
    {
        return $this->status === 'archived';
    }

    public function canAcceptDonations()
    {
        return $this->status === 'active' && !$this->isExpired();
    }

    public function daysUntilArchive()
    {
        if (!$this->completed_at || $this->archived_at) {
            return null;
        }

        $archiveDate = $this->completed_at->addDays($this->days_until_archive);
        return max(0, now()->diffInDays($archiveDate, false));
    }

    public function getStatusBadge()
    {
        return match($this->status) {
            'active' => $this->isExpired() ?
                '<span class="badge badge-warning">⏰ Ending Soon</span>' :
                '<span class="badge badge-success">✓ Active</span>',
            'pending' => '<span class="badge badge-warning">⏳ Pending Approval</span>',
            'expired' => '<span class="badge badge-info">✓ Campaign Ended</span>',
            'archived' => '<span class="badge badge-secondary">📦 Archived</span>',
            'rejected' => '<span class="badge badge-danger">✗ Rejected</span>',
            'draft' => '<span class="badge badge-secondary">📝 Draft</span>',
            default => '<span class="badge badge-secondary">' . ucfirst($this->status) . '</span>',
        };
    }
}