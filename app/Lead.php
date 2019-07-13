<?php

namespace App;
use DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
	use Notifiable;
    //
	    protected $fillable = [
        'businessName','businessNature', 'description', 'user_id','company_pro','testimonials','sol_ser','fb_link','fb_likes','tw_link','tw_followers','in_link','in_followers','li_link','li_visitors','web_link','leadstatus',
    ];
	
    protected $dates = [
        'created_at',
        'updated_at'
    ];
	
	
	public function user()
    {
        //return $this->belongsTo('App\User');
		return $this->belongsTo('App\User', 'user_id');
    }
	
	public function recording()
    {
		return $this->hasMany('App\Recording');
    }
	
	// for NOTIFICATIONS on topnavbar	//begin
	public function lead_notification()
	{
		return $lead = DB::table('notifications')
		 ->select('*')
		 ->where('notifiable_type', 'App\Lead')
		 ->whereDate('created_at', date('Y-m-d') )
		 ->get();
	}
	// for NOTIFICATIONS on topnavbar	//end
	
}


