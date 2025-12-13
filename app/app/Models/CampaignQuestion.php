<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CampaignQuestion extends Model
{
    protected $fillable = [
        'campaign_id',
        'user_id',
        'guest_name',
        'guest_email',
        'question',
        'answer',
        'answered_at',
        'is_pinned',
    ];

    protected $casts = [
        'answered_at' => 'datetime',
        'is_pinned' => 'boolean',
    ];

    /**
     * Get the campaign that owns the question
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the user who asked the question
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the question has been answered
     */
    public function isAnswered(): bool
    {
        return !is_null($this->answer);
    }

    /**
     * Scope to get only answered questions
     */
    public function scopeAnswered($query)
    {
        return $query->whereNotNull('answer');
    }

    /**
     * Scope to get only unanswered questions
     */
    public function scopeUnanswered($query)
    {
        return $query->whereNull('answer');
    }

    /**
     * Scope to get pinned questions
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    /**
     * Check if the question was asked by a guest
     */
    public function isGuest(): bool
    {
        return is_null($this->user_id);
    }

    /**
     * Get the author name (user or guest)
     */
    public function getAuthorName(): string
    {
        return $this->isGuest() ? $this->guest_name : $this->user->name;
    }

    /**
     * Get the author email (user or guest)
     */
    public function getAuthorEmail(): string
    {
        return $this->isGuest() ? $this->guest_email : $this->user->email;
    }
}
