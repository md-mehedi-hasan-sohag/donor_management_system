<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'user_id',
        'donor_name',
        'donation_type',
        'amount',
        'currency',
        'platform_fee',
        'net_amount',
        'is_anonymous',
        'is_recurring',
        'recurring_frequency',
        'next_recurring_date',
        'recurring_active',
        'in_kind_items',
        'message',
        'payment_method',
        'transaction_id',
        'payment_status',
        'payment_completed_at',
        'team_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'platform_fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'is_anonymous' => 'boolean',
        'is_recurring' => 'boolean',
        'recurring_active' => 'boolean',
        'next_recurring_date' => 'date',
        'payment_completed_at' => 'datetime',
    ];

    // Relationships
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(DonationTeam::class, 'team_id');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('payment_status', 'completed');
    }

    public function scopeMonetary($query)
    {
        return $query->where('donation_type', 'monetary');
    }

    public function scopeInKind($query)
    {
        return $query->where('donation_type', 'in_kind');
    }

    public function scopeRecurring($query)
    {
        return $query->where('is_recurring', true)
            ->where('recurring_active', true);
    }

    public function scopeDueForRecurring($query)
    {
        return $query->recurring()
            ->where('next_recurring_date', '<=', now());
    }

    // Helpers
    public function getDonorDisplayName()
    {
        if ($this->is_anonymous) {
            return 'Anonymous';
        }
        return $this->donor_name ?? $this->user->name ?? 'Guest';
    }
}