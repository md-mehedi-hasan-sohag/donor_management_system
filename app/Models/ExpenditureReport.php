<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenditureReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'title',
        'description',
        'document_path',
        'amount_reported',
        'expenditure_date',
    ];

    protected $casts = [
        'amount_reported' => 'decimal:2',
        'expenditure_date' => 'date',
    ];

    // Relationships
    public function campaign()
    {
        return $this->belongsTo(Campaign::class);
    }
}