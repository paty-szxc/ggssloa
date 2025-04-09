<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class OBForm extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'official_business';

    protected $fillable = [
        'user_id',
        'date',
        'destination',
        'purpose',
        'time_departure',
        'time_return'
    ];
}