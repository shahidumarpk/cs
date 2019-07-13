<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Spatie\Activitylog\Traits\LogsActivity;


class Attendancesheet extends Model
{
    //use LogsActivity; 
    protected $fillable = [
        'user_id', 'dated' , 'attendancedate', 'checkin', 'checkout', 'status', 'breakout', 'breakin', 'shortleaves', 'tardies', 'workedhours', 'checkoutfound', 'remarks',
        'isupdated', 'modifiedby'
    ];

    protected static $logAttributes = ['checkin', 'checkout', 'status', 'shortleaves', 'tardies', 'workedhours', 'checkoutfound', 'remarks',
    'isupdated', 'modifiedby', 'updated_at'];
    protected static $logOnlyDirty = true;
        
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

    public function modifiedby()
    {
        return $this->belongsTo('App\User', 'modifiedby');
    }

}
