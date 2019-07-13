<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//use Illuminate\Notifications\Notification;

class Recording extends Model
{
    //use Notifiable;
	
	public $table = 'recording';
    //
	protected $fillable = [
    'title','link','note','recording_file','lead_id',
    ];
	
	public function lead()
    {
        //return $this->belongsTo('App\User');
		return $this->belongsTo('App\Lead', 'lead_id');
    }
}
