<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpAccountDetails extends Model
{
    use HasFactory;

    protected $table = 'emp_account_details';

    protected $fillable = [
        'user_id',
        'bank_name',
        'acc_no',
        'ifsc',
        'salary'
    ];
}
