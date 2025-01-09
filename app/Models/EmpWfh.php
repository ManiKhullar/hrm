<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpWfh extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'partical_leave',
        'leave_type',
        'project_manager',
        'cc',
        'leave_count',
        'message',
        'status'
    ];
}
