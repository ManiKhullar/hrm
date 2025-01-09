<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpPerAddress extends Model
{
    use HasFactory;

    protected $table = 'emp_per_address';


    protected $fillable = [
        'user_id',
        'p_street',
        'p_city',
        'p_state',
        'p_country',
        'p_pincode'
    ];
}
