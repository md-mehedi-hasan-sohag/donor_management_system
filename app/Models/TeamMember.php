<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class TeamMember extends Pivot
{
    protected $table = 'team_members';
    
    public $incrementing = true;

    protected $fillable = ['team_id', 'user_id', 'role'];
}