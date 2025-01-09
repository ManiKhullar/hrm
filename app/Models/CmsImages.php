<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CmsImages extends Model
{
    use HasFactory;

    protected $table = 'cms_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'cms_id',
        'file_name',
        'status'
    ];
}
