<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpSkill extends Model
{
    use HasFactory;

    protected $table = 'emp_skill';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'skill_id',
        'user_id',
        'skill_level',
        'experience',
        'status'
    ];
}
