<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignUpdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'update_type',
        'title',
        'content',
        'image_path',
        'video_path',
        'milestone_percentage',
    ];

    protected $casts = [
        'milestone_percentage' => 'integer',
    ];

    // Relationships
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }

    // Scopes
    public function scopeProgress($query)
    {
        return $query->where('update_type', 'progress');
    }

    public function scopeMilestone($query)
    {
        return $query->where('update_type', 'milestone');
    }

    public function scopeExpenditure($query)
    {
        return $query->where('update_type', 'expenditure');
    }
}