<?php

namespace App;
//use Laravel\Passport\HasApiTokens;
//use Illuminate\Notifications\Notifiable;
//use Yadahan\AuthenticationLog\AuthenticationLogable;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use Spatie\Activitylog\Traits\LogsActivity;


class User extends Authenticatable
{
    //use HasApiTokens, Notifiable, AuthenticationLogable, LogsActivity; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fname', 'lname' , 'email' , 'avatar' ,'status', 'isGoOnAppoints', 'role_id', 'designation_id', 'department_id', 'createdby', 'updatedby'
    ];

    protected static $logAttributes = ['fname', 'lname' , 'email' , 'avatar' ,'status', 'isGoOnAppoints', 'role_id', 'designation_id', 'department_id', 'createdby', 'updatedby'];
    protected static $logOnlyDirty = true;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

     public function projects()
    {
        return $this->belongsToMany(Project::class, 'user_project');
    }

    public function role(){
        return $this->hasOne(Role::class, 'id', 'role_id' )->withDefault();
    }

    public function staffdetails(){
        return $this->hasOne(Staffdetail::class, 'user_id', 'id' )->withDefault();
    }
    
    public function department(){
        return $this->hasOne(Department::class, 'id', 'department_id' )->withDefault();
    }

    public function designation(){
        return $this->hasOne(Designation::class, 'id', 'designation_id')->withDefault();
    }

    public function leaves()
    {
        //return $this->hasMany(Leave::class,'user_id','id');
        return $this->hasMany(Leave::class,'user_id');
    }
    public function attendancelog()
    {
        return $this->hasMany(Attendance::class,'id','user_id');
    }
    
    public function attendance()
    {
        return $this->hasMany(Attendancesheet::class,'user_id');
    }

    public function deductions()
    {
        return $this->hasMany(Adjustment::class,'user_id')->where('type', 0);
    }

    
    public function additions()
    {
        return $this->hasMany(Adjustment::class,'user_id')->where('type', 1);
    }

    public function salary()
    {
        return $this->hasMany(Salarysheet::class,'id','user_id');
    }


    public function yccref()
    {
        return $this->hasMany(Yccref::class,'user_id');
    }

    
       
    public function conversation()
    {
        return $this->hasMany(Conversation::class,'id','created_by');
    }

    public function appointments()
    {
        return $this->belongsToMany('App\Appointment');
    }

    public function yccleadstatus()
    {
        return $this->belongsToMany('App\Yccleadstatus');
    }
 
    public function hasAccess(array $permissions)
    {
        if($this->role->hasAccess($permissions)){
            return true;
        }
        return false;
    }

	
//CCMS 		Start	
		
	public function createdby_self()
	{
		return $this->belongsTo('App\User', 'createdby')->withDefault();
	}	
	
	public function parent_name(){
		return $this->belongsTo('App\User','parent_id','id')->withDefault();
	}

	public function student_gender_dob(){
		return $this->belongsTo('App\Studentdetail','id','user_id')->withDefault();
	}

	public function parentdetail_relation(){
		return $this->belongsTo('App\Parentdetail','id','user_id')->withDefault();
	}	
	
	public function teachercourse_mdl()
    {
        return $this->belongsToMany(TeacherCourse::class,'teacher_courses','teacher_id')->withDefault();
    }
	
    /**
     * The teams that belong to the user.
     */
    public function teams()
    {
        return $this->belongsToMany('App\Team','team_user')->withPivot('user_id','team_id','id');
    }

//CCMS	end	
	
    /*public function routeNotificationForMail($notification)
    {
        return $this->officialemail;
    }*/
}
