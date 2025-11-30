<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
    ];

    // Relationships
    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    // Helpers
    public function activeCampaignsCount()
    {
        return $this->campaigns()->where('status', 'active')->count();
    }

    public function totalRaised()
    {
        return $this->campaigns()
            ->where('status', 'active')
            ->sum('current_amount');
    }
}