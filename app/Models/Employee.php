<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\LeaveDetail;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'employees';

    protected $fillable = [
        'emp_id',
        'emp_name',
        'position',
        'date_hired',
        'birthday',
        'blood_type',
        'user_id',
        'leave_renewal_data'
    ];

    protected static function boot(){
        parent::boot();

        static::creating(function ($employee) {
            //get the last employee id
            $lastEmployee = self::orderBy('emp_id', 'desc')->first();
            $lastId = $lastEmployee ? (int) substr($lastEmployee->emp_id, 5) : 0; // Extract the numeric part

            //increment and format the new id
            $newId = str_pad($lastId + 1, 4, '0', STR_PAD_LEFT);
            $employee->emp_id = '0000-' . $newId; //format as 0000-XXXX
        });
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function leave_details(){
        return $this->hasMany(LeaveDetail::class, 'user_id','user_id');
    }
    
}
