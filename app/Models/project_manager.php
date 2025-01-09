<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class project_manager extends Model
{
    use HasFactory;

    protected $table = 'project_managers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'manager_id',
        'developer_id',
        'technology_id',
        'status'
    ];
}
