<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class LeaveDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'leave_details';

    protected $fillable = [
        'user_id',
        'date_filed',
        'leave_from',
        'leave_to',
        'leave_type',
        'no_of_days',
        'filed',
        'with_pay',
        'reasons',
    ];

    
    public function user(){
        return $this->belongsTo(User::class);
    }
}
