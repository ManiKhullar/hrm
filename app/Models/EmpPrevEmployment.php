<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpPrevEmployment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start_date',
        'end_date',
        'company_name',
        'role',
        'company_emp_ref_name',
        'company_emp_ref_email',
        'company_emp_ref_mobile',
        'company_emp_ref_role'
    ];
}
