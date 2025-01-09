<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpFamilyDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'father_name',
        'mother_name',
        'number_type',
        'contact_number'
    ];
}
