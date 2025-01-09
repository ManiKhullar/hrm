<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpPolicy extends Model
{
    use HasFactory;

    protected $table = 'emp_policy';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'hr_policy_leave_mang',
        'hr_process_onboarding',
        'hr_process_offboarding',
        'status'
    ];
}
