<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpRegistrations extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employee_code',
        'dob',
        'gender',
        'job_title',
        'employment_type',
        'blood_group',
        'special_leave',
        'casual_leave',
        'sick_leave',
        'joining_date',
        'accept_policy',
        'status'
    ];
}
