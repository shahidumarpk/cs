<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendancesheetapproval extends Model
{
    protected $fillable = [
        'user_id', 'dated' , 'attendancedate', 'checkin', 'checkout', 'status', 'breakout', 'breakin', 'shortleaves', 'tardies', 'workedhours', 'checkoutfound', 'remarks',
        'isupdated', 'modifiedby'
    ];

        
    protected $dates = [
        'dated',
        'attendancedate',
        'created_at',
        'updated_at'
    ];
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function modified()
    {
        return $this->belongsTo('App\User', 'modifiedby')->withDefault();
    }

    public function approved()
    {
        return $this->belongsTo('App\User', 'approvedby')->withDefault();
    }
}
 