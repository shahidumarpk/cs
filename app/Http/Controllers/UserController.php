<?php
use App\User;
use App\Lead;
use App\Recording;
use App\Appointment;
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Hash;
use DB;
use DataTables;
use App\Notifications\RecordingPublished;
use App\Notifications\NewLead;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Gate;
use \App\Attendance;
use \App\Attendancesheet;
use \App\Preference;
use Carbon;
use DateTime;
use DatePeriod;
use DateInterval;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
		//where('iscustomer',0)->
        $users=\App\User::whereIn('id',[326])->get();
        return view('admins',compact('users'));
    }
	
    public function view_lead()
    {
		//Working according to the id passed to find() but commenting for now
/* 		$leads = \App\Lead::find(1); // find the book with id 1
		echo $leads->user; // This gives you the user array that has the lead;
		echo $leads->businessName; //This give you Lead value */
		
		//$leads = \App\user::with('lead')->get();
		$leads = \App\Lead::with('user')->get();
		return view('lead.leadview', compact('leads'));

    }

	//Detailed view of the LEADS
    public function view_lead_detail($id)
    {
		$lead_id = $id;
		//Lead Details
		$lead_detail = \App\Lead::find($lead_id);
		//Recording of the lead
		$recordings = DB::table('recording')->select('*')->where('lead_id', '=',$lead_id)->get();
		return view('lead.lead_detail', compact('lead_detail','recordings'));
		
		
    }
	
	
    public function view_recording()
    {

		$record = \App\Recording::with('lead')->get();;
		return view('recording.recordingview', compact('record'));

    }
	
	//Detailed view of the RECORDINGS
    public function view_recording_detail($id)
    {
		$recording_id = $id;
		//Recording Details
		$recording_detail = \App\Recording::find($recording_id);
		return view('recording.recording_detail', compact('recording_detail'));
    }

    public function view_appointment()
    {
/* 		$appointment = \App\Appointment::all();
		return view('appointment.appointmentview', compact('appointment')); */
		$appointment = \App\Appointment::with('user')->get();
		return view('appointment.appointmentview', compact ('appointment'));
    }
	
	
	

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('adminscreate');

    }
	
	public function create_lead()
    {
        //
        return view('lead.leadcreate');

    }
	

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       exit;

    }
	
	public function store_lead(Request $request)
    {
		
        exit;
    }
	
	
		
	
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        $user=\App\User::find($id);
		//agent profile and attendance		start
        //$user=auth()->user();
        //$id=$user->id;      
        //$loginlogs=\App\User::find($id)->authentications;
        if($request->get('srchmonth')){
            $srchmonth=$request->get('srchmonth');
            $searchedMonth=$srchmonth."-01";
            $firstday=date('Y-m-01', strtotime($searchedMonth));
            $lastday=date('Y-m-t', strtotime($searchedMonth));
        }else{
            $firstday=date('Y-m-01');
            $lastday=date('Y-m-t');
            $srchmonth=date('Y-m');
        }
        $attlog=\App\Attendancesheet::where('user_id',$id)->whereBetween('dated', [$firstday , $lastday])->orderBy('dated', 'ASC')->get();
        
        return view('adminsshow',compact('user','attlog', 'srchmonth'));		
		
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        exit;
        
    }
	
	

    //For Reset Password
    public function resetPassword($id)
    {
        $user=\App\User::find($id);
        return view('resetpassword',compact('user','id'));
    }
    //For Deactivate
    public function deactivate($id)
    {
        $user=\App\User::find($id);         
        $user->status=2;
        $date=now();
        $format = date_format($date,"Y-m-d");
        $user->updated_at = strtotime($format);
        $user->save();
        return redirect()->action(
            'UserController@index'
        )->with('success', 'Staff status has been deactivated.');
    }
   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        exit;
        

    }

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       exit;
        
    }
	
   	
    public function profile(Request $request)
    {
        $user=auth()->user();
        $id=$user->id;      
        //$loginlogs=\App\User::find($id)->authentications;
        return view('profile',compact('user'));
    }

	
	
	
}
