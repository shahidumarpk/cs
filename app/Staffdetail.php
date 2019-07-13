<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Spatie\Activitylog\Traits\LogsActivity;

class Staffdetail extends Model
{
    //use LogsActivity; 

    protected $fillable = [
        'user_id', 'cstreetaddress', 'cstreetaddress2','ccity','pstreetaddress','pstreetaddress2','pcity',
        'gaurdianname','gaurdianrelation','gaurdiancontact','landline','phonenumber', 'bloodgroup','dob','cnic','passportno',
        'attendanceid','extension','ccmsid','skypeid','shift','latecomming', 'latecomming' ,'attendancecheck', 'endtime', 'starttime',  'joiningdate' , 'fileno',
    ];

    protected static $logAttributes = ['user_id', 'cstreetaddress', 'cstreetaddress2','ccity','pstreetaddress','pstreetaddress2','pcity',
    'gaurdianname','gaurdianrelation','gaurdiancontact','landline','phonenumber', 'bloodgroup','dob','cnic','passportno',
    'attendanceid','extension','ccmsid','skypeid','shift','latecomming', 'latecomming' ,'attendancecheck','endtime', 'starttime', 'created_at', 'updated_at', 'joiningdate' , 'fileno' ];
    protected static $logOnlyDirty = true;


    protected $dates = [
        'dob',
        'joiningdate',
        'endingdate',
        'created_at',
        'updated_at'
    ];
    
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id' );
    }
    
    public function hrlead(){
        return $this->belongsTo(Hrlead::class, 'hrlead_id', 'id' )->withDefault();
    }
    
}
