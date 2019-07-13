<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    //
	public $table = 'appointment';
	protected $fillable = [
    'id','description','lead_id',
    ];
	
	public function user()
	{
		return $this->belongsTo('App\User','user_id');
	}
}
