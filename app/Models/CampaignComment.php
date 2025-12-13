<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'user_id',
        'parent_id',
        'comment',
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

    public function parent()
    {
        return $this->belongsTo(CampaignComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(CampaignComment::class, 'parent_id');
    }
}