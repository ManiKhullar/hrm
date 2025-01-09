<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmpBand extends Model
{
    use HasFactory;

    protected $table = 'emp_band';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'emp_band',
        'basic_salary',
        'house_rent_allounce',
        'transport_allounce',
        'special_allounce',
        'extra_pay',
        'tds_type',
        'tds',
        'status'
    ];
}
