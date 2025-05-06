<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class OTRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ot_request';

    protected $fillable = [
        'user_id',
        'reason',
        'time_duration',
        'time_end',
        'total_hrs_mins',
        'status',
        'date',
    ];
}
