<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientMasterImport extends Model
{
    use HasFactory;

    protected $table = 'client_master_list';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'technology',
        'interview_date',
        'company',
        'name',
        'contact_person',
        'client_email',
        'contact_number',
        'source',
        'rate',
        'pre_call_notes',
        'meeting_link',
        'post_call_notes',
        'status',
        'interview_taken_by',
        'end_client',
        'interview_type'
    ];
}
