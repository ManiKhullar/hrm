<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_pic',
        'addhar_number',
        'addhar_doc_file',
        'pan_number',
        'pan_doc_file',
        'offer_letter',
        'relieving_latter',
        'resignation_letter',
        'appointment_latter',
        'bank_statment',
        'salary_slip1',
        'salary_slip2',
        'salary_slip3'
    ];
}
