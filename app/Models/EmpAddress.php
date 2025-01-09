<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpAddress extends Model
{
    use HasFactory;


    protected $fillable = [
        'user_id',
        'street',
        'city',
        'state',
        'country',
        'pincode'
    ];
}
