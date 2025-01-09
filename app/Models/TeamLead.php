<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamLead extends Model
{
    use HasFactory;

    protected $table = 'team_lead';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'teamlead_id',
        'id',
        'user_id',
        'status',
        'manager_id'
    ];
}
