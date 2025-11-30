<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
        'profile_image',
        'two_factor_enabled',
        'two_factor_secret',
        'verification_status',
        'account_status',
        'preferred_currency',
        'suspended_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'two_factor_enabled' => 'boolean',
        'suspended_at' => 'datetime',
    ];

    // Relationships
    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function verification()
    {
        return $this->hasOne(RecipientVerification::class);
    }

    public function followedCampaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_followers')
            ->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(CampaignComment::class);
    }

    public function fraudReports()
    {
        return $this->hasMany(FraudReport::class, 'reported_by');
    }

    public function reviewedReports()
    {
        return $this->hasMany(FraudReport::class, 'reviewed_by');
    }

    public function createdTeams()
    {
        return $this->hasMany(DonationTeam::class, 'created_by');
    }

    // public function teams()
    // {
    //     return $this->belongsToMany(DonationTeam::class, 'team_members')
    //         ->withPivot('role')
    //         ->withTimestamps();
    // }
    public function teams()
    {
    return $this->belongsToMany(DonationTeam::class, 'team_members', 'user_id', 'team_id')
        ->withPivot('role')
        ->withTimestamps();
    }

    public function volunteerSignups()
    {
        return $this->hasMany(VolunteerSignup::class);
    }

    public function badges()
    {
    return $this->belongsToMany(DonorBadge::class, 'user_badges', 'user_id', 'badge_id')
        ->withTimestamps();
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // Scopes
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    public function scopeDonors($query)
    {
        return $query->where('role', 'donor');
    }

    public function scopeRecipients($query)
    {
        return $query->where('role', 'recipient');
    }

    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function scopeActive($query)
    {
        return $query->where('account_status', 'active');
    }

    // Helpers
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isDonor()
    {
        return $this->role === 'donor';
    }

    public function isRecipient()
    {
        return $this->role === 'recipient';
    }

    public function isVerified()
    {
        return $this->verification_status === 'verified';
    }

    public function totalDonated()
    {
        return $this->donations()
            ->where('payment_status', 'completed')
            ->sum('amount');
    }

    public function totalCampaignsSupported()
    {
        return $this->donations()
            ->where('payment_status', 'completed')
            ->distinct('campaign_id')
            ->count('campaign_id');
    }
}