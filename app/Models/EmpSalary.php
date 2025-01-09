<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpSalary extends Model
{
    use HasFactory;

    protected $table = 'emp_salary';

    protected $fillable = [
        'user_id',
        'month',
        'year',
        'credit_salary'
    ];
}
