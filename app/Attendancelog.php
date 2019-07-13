<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendancelog extends Model
{
    protected $fillable = [
        'user_id', 'attendancedate', 'attendancetime', 'machineuserid', 'status'
    ];
        
    protected $dates = [
        'attendancedate',
        'created_at',
        'updated_at'
    ];
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
