<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpCommunication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'mobile_number',
        'company_email_id',
        'internal_email_id',
        'email_id'
    ];
}
