<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Validator;
use DB;
use Datatables;

use \App\User;
use \App\Designation;
use \App\Department;
use \App\Staffdetail;
use \App\Attendance;
use \App\Attendancesheet;
use \App\Attendancesheetapproval;
use \App\Hrlead;
use \App\Preference;
use \App\Holiday;
use Carbon;
use DateTime;
use DatePeriod;
use DateInterval;
use Redirect;
use Spatie\Activitylog\Models\Activity;

class AttendancesheetController extends Controller
{
    public function index(Request $request)
    {

        if($request->get('srchmonth')){
            $srchmonth=$request->get('srchmonth');
            $searchedMonth=$srchmonth."-01";
            $firstday=new DateTime(date('Y-m-01', strtotime($searchedMonth)));
            $lastday=new DateTime(date('Y-m-t', strtotime($searchedMonth)));
            $monthlastday=$lastday;
            $monthlastdayforcommission=new DateTime(date('Y-m-t', strtotime($searchedMonth)));
            
            $yesterday=$lastday;
            $begin = $firstday;
            $end = $lastday;          
            $end = $end->modify( '+1 day' ); 
        }else{
            //echo "123";
            //Creating Monthly Date range begins
            $firstday = new DateTime('first day of this month');
            $firstday->format('Y-m-d');
            $lastday = new DateTime('last day of this month');
            $monthlastday = new DateTime('last day of this month');
            $monthlastdayforcommission = new DateTime('last day of this month');
            /*$lastday = new DateTime();
            $lastday = $lastday->modify( '-1 day' );*/
            $yesterday=$lastday->format('Y-m-d');
            $begin = $firstday;
            $end = $lastday;
            $end = $end->modify( '+1 day' ); 
            $srchmonth=date("Y-m");
        }

        
        
        
        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval ,$end);
        //Creating Monthly Date range ends
        //Get Active Staff begins
        $deparment_id=array();
        
        
        if($request->get('deparment_id')){
            
            $deparment_id=explode(",", $request->get('deparment_id'));
            $employees=User::where('iscustomer',0)
                    //->where('status',1)
                    ->whereIn('department_id', $deparment_id)
                    ->withCount(['deductions' => function ($query) use($firstday, $monthlastday)  {
                        $query->select(DB::raw('sum(amount)'))->where('status','Approved')->whereBetween('dated', [$firstday, $monthlastday]);
                    }])
                    ->withCount(['additions' => function ($query) use($firstday, $monthlastday) {
                        $query->select(DB::raw('sum(amount)'))->where('status','Approved')->whereBetween('dated', [$firstday, $monthlastday]);
                    }])
                    ->whereHas('staffdetails', function ($query) {
                        $query->where('showinsalary', '=', 1);
                    })
                    ->get();
        
        }else{
            $employees=User::where('iscustomer',0)
                //->where('status',1)
                ->withCount(['deductions' => function ($query) use($firstday, $monthlastday)  {
                    $query->select(DB::raw('sum(amount)'))->where('status','Approved')->whereBetween('dated', [$firstday, $monthlastday]);
                }])
                ->withCount(['additions' => function ($query) use($firstday, $monthlastday) {
                    $query->select(DB::raw('sum(amount)'))->where('status','Approved')->whereBetween('dated', [$firstday, $monthlastday]);
                }])
                ->whereHas('staffdetails', function ($query) {
                    $query->where('showinsalary', '=', 1);
                })
                ->get();
            
        }
        //Get Active Staff ends
        //Get salaries from CCMS begins
    
        $ch = curl_init();
        //$url="http://192.168.5.15:82/ccms_api/comm_teacher_agent_management_prr_ver2_api.php";
        $url="https://www.yourcloudcampus.com/ccms_business_api/comm_teacher_agent_management_prr_ver2_api.php";
        $fromDate=$firstday->format('Y-m-d');
        $toDate=$monthlastdayforcommission->format('Y-m-d');
        $qrystring="?fromDate=$fromDate&toDate=$toDate";
        curl_setopt($ch, CURLOPT_URL,$url.$qrystring);     
        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ccmsdata = curl_exec($ch);
        curl_close ($ch);
        // Further processing ...
        $salariesdata=json_decode($ccmsdata);
        if(!empty($salariesdata)){
            foreach($salariesdata->comm as $empcomm){
                if(!empty($empcomm->id) or $empcomm->id!=null){
                    $salaries[$empcomm->id]=$empcomm;
                }
            }
        }
        
        //Get salaries from CCMS ends
        //Get emp attendance data begins
            foreach($employees as $emp){          
                $attendance=array();
                $empExitdate=$emp->staffdetails->joiningdate->format('Y-m-d');
                //dd($emp->staffdetails->joiningdate->format('Y-m-d'));
                foreach($daterange as $dt){
                    $empExitdate=$emp->staffdetails->joiningdate->format('m-Y');
                    $att_data=Attendancesheet::where('dated',$dt->format('Y-m-d'))->where('user_id',$emp->id)->first();
                    if(!empty($att_data)){
                        $att_data->dayno=$dt->format('d-M');
                        $attendance[$dt->format('Y-m-d')]=$att_data;
                    }else{
                        $attendance[$dt->format('Y-m-d')]=[
                            'dated' => $dt->format('Y-m-d'),
                            'dayno' => $dt->format('d-M'),
                            'status' => '-',
                            'dayname' => $dt->format('D'),
                            'checkin' => 0,
                            'checkout' => 0,
                            'shortleaves' => 0,
                            'tardies' => 0,
                            'workedhours' => 0,
                            'checkoutfound' => 'No',
                            'paid' => 0,
                            'remarks' => '-',
                            'workedhours' => 0,

                        ];
                    }

                }
                //dd($attendance);
                $emp['att']=$attendance;   
                if(!empty($salaries[$emp->staffdetails->ccmsid])){
                    $empccmssalarydata=$salaries[$emp->staffdetails->ccmsid];
                    $emp['salary']=$empccmssalarydata->comm_sum + $empccmssalarydata->salary;
                }else{
                    $emp['salary']=0;
                }
                $emps[]=$emp;
            }
            $employees=$emps;
        //dd($employees);
        //Get emp attendance data ends
        //Get preferences begins
        $preferences= \App\Preference::whereIn('option',['tardydaydeduct','shortleavedaydeduct', 'daysinmonth','absentfine'])->get();
            
        foreach($preferences as $preference){
            if($preference->option=='tardydaydeduct'){
                $settings['tardydaydeduct']=$preference->value;
            }
            if($preference->option=='shortleavedaydeduct'){
                $settings['shortleavedaydeduct']=$preference->value;
            }
            if($preference->option=='daysinmonth'){
                $settings['daysinmonth']=$preference->value;
            }
            if($preference->option=='absentfine'){
                $settings['absentfine']=$preference->value;
            }
        }
        //Get preferences ends
        //Get Deparments begins
        $departments = Department::where('status', 1)->orderBy('deptname', 'ASC')->get();
        //Get Deparments ends
        return view('attendancesheet.index',compact('daterange','employees','settings','departments','deparment_id','srchmonth'));
    }

    public function edit(Request $request)
    {
      $curent_date=date("m-Y");
      $data = Attendancesheet::findOrFail($request->id);
      if(!empty($data)){
        $db_date=date("m-Y",strtotime($data->dated));
      }
      return response()->json($data);
      /*if($curent_date!=$db_date){
        return  response()->json(['errors'=>"You can't edit this record, its not belongs to current month."]);
      }else {
        return response()->json($data);
      } */     
    }

    public function update(Request $request)
    {
        $curent_date=date("m-Y");
        $attdata = Attendancesheet::findOrFail($request->id);
        
        
        $att = New Attendancesheetapproval;
        $att->user_id=$attdata->user_id;
        $att->dated=$attdata->dated;
        $att->dayname=$attdata->dayname;
        $att->attendancedate=$attdata->attendancedate;
        $att->paid=$attdata->paid;
        $att->ref_id=$attdata->id;
        $att->remarks=$request->get('remarks');
        $att->checkin=$request->get('checkin');
        $att->checkout=$request->get('checkout');
        $att->checkoutfound=$request->get('checkoutfound');
        $att->tardies=$request->get('tardies');
        $att->shortleaves=$request->get('shortleaves');
        $att->workedhours=$request->get('workedhours');
        $att->status=$request->get('status');
        $att->approvestatus=0;
        $att->isupdated=1;        
        $att->modifiedby=auth()->user()->id;
        $date=date_create($request->get('date'));
        $format = date_format($date,"Y-m-d H:i:s");
        $att->created_at = strtotime($format);
        $att->save();

        return response()->json(['success'=>'Record has been sent for approval successfully.']);
    }
    /* Approval view Begins */
    public function approval()
    {
        return view('attendancesheet.approval');
    }
    public function approvalfetch(Request $request)
    {
       
        $columns = array( 
                            0 =>'id', 
                            1 =>'dated',
                            2=> 'dayname',
                            3=> 'checkin',
                            4=> 'checkout',
                            5=> 'checkoutfound',
                            6 =>'tardies',
                            7=> 'shortleaves',
                            8=> 'workedhours',
                            9=> 'remarks',
                            10=> 'modifiedby',
                            11 =>'status',
                            12=> 'approvestatus',
                            13=> 'created_at',
                            14=> 'approvedby',
                            15=> 'updated_at',
                            15=> 'created_at',
                            15=> 'user_id',
                            16=> 'id',
                            
                        );

         $totalData = Attendancesheetapproval::count();   
         $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if(empty($request->input('search.value')))
        {            
            
                $data = Attendancesheetapproval::offset($start)->limit($limit)
                         ->orderBy($order, $dir)
                         ->get();
        }else{
            $search = $request->input('search.value'); 

            $data = Attendancesheetapproval::where('id', 'LIKE', '%' . $search . '%')
                              ->orwhere('deptname', 'LIKE', '%' . $search . '%')
                              ->orwhere('status', 'LIKE', '%' . $search . '%')
                              ->offset($start)
                              ->limit($limit)
                              ->orderBy($order, $dir)
                              ->get();


            $totalFiltered = Attendancesheetapproval::where('id', 'LIKE', '%' . $search . '%')
                              ->orwhere('deptname', 'LIKE', '%' . $search . '%')
                              ->orwhere('status', 'LIKE', '%' . $search . '%')
                              ->count();                  
           
        }


        $dataArray = array();
        if(!empty($data))
        {
            foreach ($data as $row)
            {
                $id = $row->id;               
                $nestedData['id']  = $row->id;
                $nestedData['empname'] = $row->user->fname .' '.$row->user->lname;
                $nestedData['dated'] = $row->dated->format('d-M-Y \(l\)');
                $nestedData['dayname'] = $row->dayname;
                $nestedData['checkin'] = $row->checkin;
                $nestedData['checkout'] = $row->checkout;
                $nestedData['checkoutfound'] = $row->checkoutfound;
                $nestedData['tardies'] = $row->tardies;
                $nestedData['shortleaves'] = $row->shortleaves;
                $nestedData['workedhours'] = $row->workedhours;
                $nestedData['remarks'] = $row->remarks;
                $nestedData['modifiedby'] = $row->modified->fname .' '.$row->modified->lname;
                $nestedData['status'] = $row->status;
                $nestedData['approvestatus'] = $row->approvestatus;
                $nestedData['approvedby'] = $row->approved->fname .' '.$row->approved->lname;
                $nestedData['updated_at'] = $row->updated_at->format('d-M-Y');
                $nestedData['created_at'] = $row->created_at->format('d-M-Y');
                $view="";
                $token=csrf_token();
                if($row->approvestatus=='1') {
                  $nestedData['approvestatus'] = '<span class="btn btn-success btn-sm">Approved</span>';
                }else if($row->approvestatus=='2'){
                  $nestedData['approvestatus'] = '<span class="btn btn-danger btn-sm">Rejected</span>';
                }else if($row->approvestatus=='0'){
                    $nestedData['approvestatus'] = '<span class="btn btn-warning btn-sm">Pending</span>';
                  }
                if(Auth::user()->can('att-viewapproval') && $row->approvestatus!='1'){
                    $view="<a class='btn btn-primary view' href='javascript:void(0)' data-id='{$id}'><i class='fa fa-eye'></i></a> ";
                }
                $nestedData['options'] = $view;                             
                
                $dataArray[] = $nestedData;

            }
        }
          
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $dataArray   
                    );
          return response()->json($json_data);   
    }
    //View begins
    public function viewapproval(Request $request)
    {
        $attunapprove = Attendancesheetapproval::findOrFail($request->id);      
        $attdata = Attendancesheet::findOrFail($attunapprove->ref_id);
        $empname=$attunapprove->user->fname.' '.$attunapprove->user->lname;
        if(empty($attdata)){
            return response()->json(['errors'=>'This request has no relevant data.']);
        }
        $keydata=['id','user_id','attendancedate','dayname','breakout','breakin','created_at','updated_at','isupdated','modifiedby', 'paid'];
        $keydates=['dated'];
        $data='<div class="table-responsive"><table class="table table-bordered table-striped dataTable" style="width:100%">
                  <thead>
                    <tr>
                      <th>Columns</th>
                      <th>Old</th>
                      <th>New</th>
                    </tr>
                    </thead>
                    <tbody>';
        foreach($attdata->toArray() as $key => $val){
            if(!in_array($key, $keydata)){
                if(in_array($key, $keydates)){
                    $value=$attdata->$key->format('d-M-Y');
                    $newvalue=$attunapprove->$key->format('d-M-Y');
                }else{
                    $value=$val;
                    $newvalue=$attunapprove->$key;
                }
                if($value!=$newvalue){
                    $style="text-red";
                }else{
                    $style="";
                }
                $data.='<tr><td>'.strtoupper($key).'</td><td>'.$value.'</td><td class='.$style.'>'.$newvalue.'</td></tr>';
            }
        }

        $data.='</tbody></table></div>';
        return response()->json(['success'=>'Data found.' , 'data' => $data, 'empname' => $empname ,'approvestatus' => $attunapprove->approvestatus ]);
    }
    //View ends
    /* Approval view ends */
    public function approve(Request $request)
    {
        $this->authorize('att-approve');   
        $attunapprove = Attendancesheetapproval::findOrFail($request->id);
        if($attunapprove->approvestatus==1){
            return response()->json(['errors'=>'This record has already approved.']);
        }
        $date=date_create($request->get('date'));
        $format = date_format($date,"Y-m-d H:i:s");
       
        
        $att = Attendancesheet::findOrFail($attunapprove->ref_id);
        if(empty($att)){
            return response()->json(['errors'=>'This request has no relevant data.']);
        }
        /*
        if(!empty($att)){
            $db_date=date("m-Y",strtotime($att->dated));
        }
        if($curent_date!=$db_date){
            return  response()->json(['errors'=>"You can't edit this record, its not belongs to current month."]);
        }*/
        //Update main attendance sheet begins
        $att->remarks=$attunapprove->remarks;
        $att->checkin=$attunapprove->checkin;
        $att->checkout=$attunapprove->checkout;
        $att->checkoutfound=$attunapprove->checkoutfound;
        $att->tardies=$attunapprove->tardies;
        $att->shortleaves=$attunapprove->shortleaves;
        $att->workedhours=$attunapprove->workedhours;
        $att->status=$attunapprove->status;
        $att->isupdated=1;
        $att->modifiedby=$attunapprove->modifiedby;
        $att->updated_at = strtotime($format);
        $att->save();
        //Activity Log begins
        $activity = Activity::all()->last();
        $activity->description; 
        $activity->subject; 
        //Activity Log ends
        //Update main attendance sheet ends
        //Update status to Approved begins
        $attunapprove->approvestatus=1;
        $attunapprove->approvedby=auth()->user()->id;
        $attunapprove->updated_at=strtotime($format);
        $attunapprove->save();
        //Update status to Approved ends
         

        return response()->json(['success'=>'This record has been approved.']);
    }

    public function reject(Request $request)
    {
        $attunapprove = Attendancesheetapproval::findOrFail($request->id);
        if($attunapprove->approvestatus==2){
            return response()->json(['errors'=>'This record has already rejected.']);
        }
        $date=date_create($request->get('date'));
        $format = date_format($date,"Y-m-d H:i:s");
       
        //Update status to Approved begins
        $attunapprove->approvestatus=2;
        $attunapprove->approvedby=auth()->user()->id;
        $attunapprove->updated_at=strtotime($format);
        $attunapprove->save();
        //Update status to Approved ends
         

        return response()->json(['success'=>'This record has been Rejected.']);
    }

    

    //Lock Salary Sheet Begins
    public function locksalarysheet(Request $request)
    {
        
        if($request->get('dated')){
            $srchmonth=$request->get('dated');
            $searchedMonth=$srchmonth;
            $firstday=new DateTime(date('Y-m-01', strtotime($searchedMonth)));
            $lastday=new DateTime(date('Y-m-t', strtotime($searchedMonth)));
            $monthlastday=$lastday;
            $yesterday=$lastday;
            $begin = $firstday;
            $end = $lastday;          
            $end = $end->modify( '+1 day' ); 
            $monthlastdayforcommission=new DateTime(date('Y-m-t', strtotime($searchedMonth)));
        }else{
            //Creating Monthly Date range begins
            $firstday = new DateTime('first day of this month');
            $firstday->format('Y-m-d');
            $lastday = new DateTime('last day of this month');
            $monthlastday = new DateTime('last day of this month');
            $yesterday=$lastday;
            $begin = $firstday;
            $end = $lastday;
            $end = $end->modify( '+1 day' ); 
            $searchedMonth=date("Y-m-01");
            $monthlastdayforcommission = new DateTime('last day of this month');
        }
        

        //Check If Salary Sheet already executed for the required month begins       
        $recordexisit = \App\Salarysheet::where('dated', $searchedMonth)->count();
        if($recordexisit > 0){
            //return redirect('staff/attendancesheet')->with('failed', 'This sheet is already locked');
            return Redirect::back()->with('failed', 'This sheet is already locked');

        }
        //Check If Salary Sheet already executed for the required month ends
        
        
        
        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($begin, $interval ,$end);
        //Creating Monthly Date range ends
        //Get Active Staff begins
        $employees=User::where('iscustomer',0)
            //->where('status',1)
            ->withCount(['deductions' => function ($query) use($firstday, $monthlastday)  {
                $query->select(DB::raw('sum(amount)'))->where('status','Approved')->whereBetween('dated', [$firstday, $monthlastday]);
            }])
            ->withCount(['additions' => function ($query) use($firstday, $monthlastday) {
                $query->select(DB::raw('sum(amount)'))->where('status','Approved')->whereBetween('dated', [$firstday, $monthlastday]);
            }])
            ->whereHas('staffdetails', function ($query) {
                $query->where('showinsalary', '=', 1);
            })
            ->get();
        //Get Active Staff ends
        //Get salaries from CCMS begins
    
        $ch = curl_init();
        //$url="http://192.168.5.15:82/ccms_api/comm_teacher_agent_management_prr_ver2_api.php";
        $url="https://www.yourcloudcampus.com/ccms_business_api/comm_teacher_agent_management_prr_ver2_api.php";
        $fromDate=$firstday->format('Y-m-d');
        $toDate=$monthlastdayforcommission->format('Y-m-d');
        $qrystring="?fromDate=$fromDate&toDate=$toDate";
        curl_setopt($ch, CURLOPT_URL,$url.$qrystring);     
        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ccmsdata = curl_exec($ch);
        curl_close ($ch);
        // Further processing ...
        $salariesdata=json_decode($ccmsdata);
        if(!empty($salariesdata)){
            foreach($salariesdata->comm as $empcomm){
                if(!empty($empcomm->id) or $empcomm->id!=null){
                    $salaries[$empcomm->id]=$empcomm;
                }
            }
        }
        //Get salaries from CCMS ends
        //Get emp attendance data begins
            foreach($employees as $emp){          
                $attendance=array();
                foreach($daterange as $dt){
                    $att_data=Attendancesheet::where('dated',$dt->format('Y-m-d'))->where('user_id',$emp->id)->first();
                    if(!empty($att_data)){
                        $attendance[$dt->format('Y-m-d')]=$att_data;
                    }else{
                        $attendance[$dt->format('Y-m-d')]=[
                            'dated' => $dt->format('Y-m-d'),
                            'status' => '-',
                            'dayname' => $dt->format('D'),
                            'checkin' => 0,
                            'checkout' => 0,
                            'shortleaves' => 0,
                            'tardies' => 0,
                            'workedhours' => 0,
                            'checkoutfound' => 'No',
                            'paid' => 0,
                            'remarks' => '-',
                            'workedhours' => 0,

                        ];
                    }

                }
                //dd($attendance);
                $emp['att']=$attendance;   
                if(!empty($salaries[$emp->staffdetails->ccmsid])){
                    $empccmssalarydata=$salaries[$emp->staffdetails->ccmsid];
                    $emp['salary']=$empccmssalarydata->comm_sum + $empccmssalarydata->salary;
                }else{
                    $emp['salary']=0;
                }
                $emps[]=$emp;
            }
            $employees=$emps;
        //dd($employees);
        //Get emp attendance data ends
        //Get preferences begins
        $preferences= \App\Preference::whereIn('option',['tardydaydeduct','shortleavedaydeduct', 'daysinmonth','absentfine'])->get();
            
        foreach($preferences as $preference){
            if($preference->option=='tardydaydeduct'){
                $settings['tardydaydeduct']=$preference->value;
            }
            if($preference->option=='shortleavedaydeduct'){
                $settings['shortleavedaydeduct']=$preference->value;
            }
            if($preference->option=='daysinmonth'){
                $settings['daysinmonth']=$preference->value;
            }
            if($preference->option=='absentfine'){
                $settings['absentfine']=$preference->value;
            }
        }
        //Get preferences ends
        //return view('attendancesheet.index',compact('daterange','employees','settings','departments','deparment_id','srchmonth'));

        //Calculating Salary begins
        $salarydata=[
            'daterange' => $daterange,
            'employees' => $employees,
            'settings' => $settings,
            'srchmonth' => $srchmonth,

        ];
        //dd($salarydata);
        DB::beginTransaction();
        try{
            foreach($employees as $emp){
                $joiningmonth=$emp->staffdetails->joiningdate->format('Y-m-d');
                $thismonth=date('Y-m-t' , strtotime($request->get('dated')));
                $to = \Carbon\Carbon::createFromFormat('Y-m-d', $joiningmonth);
                $from = \Carbon\Carbon::createFromFormat('Y-m-d', $thismonth);
                if($to->lessThan($from)){
                    $basicsalary=($emp->staffdetails->salary) ? $emp->staffdetails->salary : '0.00';              
                    $grosssalary=$emp->salary+$basicsalary;
                    
                    $user_id=$emp->id;
                    $tardis=0;
                    $shortleave=0;
                    $absents=0;
                    $leaves=0;
                    $upleaves=0;
                    $presents=0;
                    $unpaiddays=0;
                    $totaldays=0;
                    $deducteddays=0;
                    $salarydeduction=0;
                    $deductiontotal=0;
                    $absentfine=0;
                    $deductions_count=0;
                    foreach($emp->att as $att){
                        $tardis+=$att['tardies'];
                        $shortleave+=$att['shortleaves'];
                        if($att['status']=='X'){
                            $absents++;
                            $absentfine+=$settings['absentfine'];
                        }elseif($att['status']=='P' or $att['status']=='H'){
                            $presents++;
                        }elseif($att['status']=='-'){
                            $unpaiddays++;
                        }elseif($att['status']=='UL'){
                            $upleaves++;
                        }else{
                            $leaves++;
                        }
                        $totaldays++; 
                    }
                    //Salary Calculation beings
                    //Deduction plus fine
                    $deductions_count =($emp->deductions_count) ? $emp->deductions_count : 0;
                    //$deductions_count+= $absentfine;
                    //Tardy conversion to deducted days
                    $deducteddays+=intdiv($tardis,$settings['tardydaydeduct']);
                    //Short leaves conversion to deducted days
                    $deducteddays+=intdiv($shortleave,$settings['shortleavedaydeduct']);
                    //Absents + Unpaid Leave + Unpaid days
                    $deducteddays+=$absents+$upleaves+$unpaiddays;
                    //Per day salary
                    $perdaysalary=$grosssalary/$settings['daysinmonth'];                        
                    //Unpaid days salary
                    $salarydeduction=$perdaysalary * $deducteddays;
                    if($salarydeduction > $grosssalary){
                        $salarydeduction=$grosssalary;
                    }
                    //Total deductions
                    $deductiontotal=$salarydeduction + $emp->deductions_count + $absentfine;
                    //$totaldeductions+=$emp->deductions_count + $absentfine;
                    //Net salary
                    $netsalary=$grosssalary - $deductiontotal  + $emp->additions_count;
                    if($netsalary < 0){
                        $netsalary=0;
                    }
                    //Salary Calculation ends
                    //Emp Salary Calculation ends

                    //Inserting in DB begins
                    $salarysheet= new \App\Salarysheet;
                    $salarysheet->dated=$searchedMonth;
                    $salarysheet->user_id=$user_id;
                    $salarysheet->tardies=$tardis;
                    $salarysheet->shortleaves=$shortleave;
                    $salarysheet->absents=$absents;
                    $salarysheet->paidleaves=$leaves;
                    $salarysheet->unpaidleaves=$upleaves;
                    $salarysheet->presents=$presents;
                    $salarysheet->totaldays=$totaldays;
                    $salarysheet->deductedays=$deducteddays;
                    $salarysheet->basicsalary=$basicsalary;
                    $salarysheet->earnedsalary=($emp->salary) ? $emp->salary : 0;
                    $salarysheet->grosssalary=$grosssalary;
                    $salarysheet->otherdeductions=$deductions_count;
                    $salarysheet->absentfine=$absentfine;
                    $salarysheet->salarydeductions=$salarydeduction;
                    $salarysheet->additions=($emp->additions_count) ? $emp->additions_count : 0;
                    $salarysheet->totaldeductions=$deductiontotal;
                    $salarysheet->perdaysalary=$perdaysalary;
                    $salarysheet->netsalary=$netsalary;
                    $salarysheet->status='Unpaid';
                    $salarysheet->amountpaid=0;
                    $salarysheet->created_by = auth()->user()->id;
                    $salarysheet->modified_by = auth()->user()->id;
                    $date=date_create($request->get('date'));
                    $format = date_format($date,"Y-m-d");
                    $salarysheet->created_at = strtotime($format);
                    $salarysheet->updated_at = strtotime($format);
                    $salarysheet->save();
                    //Inserting in DB ends
                }
            }
        DB::commit();
        //return redirect('staff/attendancesheet',['srchmonth' => date('Y-m', strtotime($searchedMonth))])->with('success', 'Salary sheet created successfully for the month of: '. date('M-Y', strtotime($searchedMonth)));
        Redirect::back()->with('success', 'Salary sheet created successfully for the month of: '. date('M-Y', strtotime($searchedMonth)));
        exit;
        }catch(\Exception $e){
            DB::rollback();  
            //return redirect('staff/attendancesheet',['srchmonth' => date('Y-m', strtotime($searchedMonth))])->with('failed', 'Unable to Lock Salary sheet, Please try again later after fixing the problem.\n'.$e->getMessage());
            return Redirect::back()->with('failed', 'Unable to Lock Salary sheet, Please try again later after fixing the problem.\n'.$e->getMessage());
        }
        //Calculating Salary ends

    }
    //Lock Salary Sheet Ends

    public function getleaves(){
        //exit;
        $ch = curl_init();      
        //Live
        $url="https://www.yourcloudcampus.com/ccms_business_api/leave_application_list_hr_api.php?fromDate=2019-03-01&toDate=2019-03-31";
        
        curl_setopt($ch, CURLOPT_URL,$url);     
        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ccmsdata = curl_exec($ch);
        curl_close ($ch);
        // Further processing ...
        
        $data=json_decode($ccmsdata);
        
        $leavetype="";
        $NoOfDays="";
        $paid=0;
        $fromDate="";
        $toDate="";
        DB::beginTransaction();
        try{
            if($data->status=='1' && !empty($data->attendance_data)){
                $leavesdata=$data->attendance_data;
                foreach($leavesdata as $empid => $empleaves){
                    foreach($empleaves as $k => $empleave){
                        //echo $empid."<br>";
                        $userdata=\App\Staffdetail::where('ccmsid', $empid)->first();
                        
                        if(!empty($userdata)){
                            $user_id=$userdata->user_id;

                            if($empleave->LeaveType!=null ){
                                switch ($empleave->LeaveType){
                                case 'Sick':
                                    $leavetype="SL";
                                    break;
                                case 'Casual':
                                    $leavetype="CL";
                                    break;
                                case 'Other':
                                    $leavetype="OL";
                                    break;
                                }
                            }else{
                                $leave="SL";
                            }
                            
                            $NoOfDays=$empleave->NoOfDays;
                            $paid=$empleave->paid;
                            $fromDate=$empleave->fromDate;
                            $toDate=$empleave->toDate;
                            if($NoOfDays == 1){
                                $leave= new \App\Leave;
                                $dated=date_create($empleave->fromDate);
                                $dtformat = date_format($dated,"Y-m-d");
                                $leave->dated = strtotime($dtformat);
                                $leave->description=$empleave->LeaveReason; 
                                $leave->leavetype=$leavetype;
                                $leave->status="Approved";     
                                $leave->ispaid=$paid;
                                $leave->isgroup=0;        
                                $leave->user_id=$user_id;
                                //$leave->user_id=1;
                                $leave->created_by=1;
                                $leave->modified_by=1;
                                $date=date_create($empleave->dated);
                                $format = date_format($date,"Y-m-d H:i:s");
                                $leave->created_at = strtotime($format);
                                $leave->updated_at = strtotime($format);
                                $leave->save();
                            }else{
                                //echo $empid."<br>";
                                //echo "<hr>".$empleave->fromDate."<br>";
                                //echo $empleave->toDate."<br>";
                                $begin=new DateTime(date('Y-m-d', strtotime($empleave->fromDate)));
                                $end=new DateTime(date('Y-m-d', strtotime($empleave->toDate)));      
                                $end = $end->modify( '+1 day' );             
                                $interval = new DateInterval('P1D');
                                $daterange = new DatePeriod($begin, $interval ,$end);
                                foreach($daterange as $dt){
                                    //echo "dated= ".$dt->format('Y-m-d')."<br>";
                                    $leave= new \App\Leave;
                                    $dated=date_create($dt->format('Y-m-d'));
                                    $dtformat = date_format($dated,"Y-m-d");
                                    $leave->dated = strtotime($dtformat);
                                    $leave->description=$empleave->LeaveReason; 
                                    $leave->leavetype=$leavetype;
                                    $leave->status="Approved";     
                                    $leave->ispaid=$paid;
                                    $leave->isgroup=0;        
                                    $leave->user_id=$user_id;
                                    //$leave->user_id=1;
                                    $leave->created_by=1;
                                    $leave->modified_by=1;
                                    $date=date_create($empleave->dated);
                                    $format = date_format($date,"Y-m-d H:i:s");
                                    $leave->created_at = strtotime($format);
                                    $leave->updated_at = strtotime($format);
                                    $leave->save();
                                }
                                //echo "<hr>";
                            }
                        }
                    }
                }
                DB::commit();
                echo "Done";
            }//End if
        }catch(\Exception $e){
            DB::rollback();  
            echo $e->getMessage();
        }

    }

    //Manual leave updation in the Attendance sheet
    public function attleaves(){
       // exit;
        $leaves=  \App\Leave::where('id','>', 833 )->get();
        echo "<pre>";
        foreach($leaves as $leave){
            echo "<hr>";
            print_r($leave->toArray());
            /*echo "<br>";
            echo $leave->dated->format('Y-m-d');
            echo "<br>";
            echo $leave->user_id;
            echo "<br>";*/
            //$attsql=  \App\Attendancesheet::where('dated', $leave->dated->format('Y-m-d'))->where('user_id',$leave->user_id)->toSql();
            //print_r($attsql);
            $att=  \App\Attendancesheet::where('dated', $leave->dated->format('Y-m-d'))->where('user_id',$leave->user_id)->first();
            $status='UL';
            if(!empty($att)){
                print_r($att->toArray());                                
                if($att->status=='X'){
                    //Update Leave Status begins
                    echo "Data found with absent.";
                    if($leave->ispaid==1){
                        $status=$leave->leavetype;
                        $paid=1;
                    }else{
                        $status='UL';
                        $paid=0;
                    }
                    $att->status=$status;
                    $att->remarks=$leave->leavetype.' - '.$leave->description;
                    $att->paid=$paid;
                    $att->save();
                    //Update Leave Status ends                   
                }else{
                    echo "Data found but not absent.";
                }
                
            }
            echo "<hr>";
        }
        
    }



    public function showleaves(){
        exit;
        $ch = curl_init();
        //Live
        $url="https://www.yourcloudcampus.com/ccms_business_api/leave_application_list_hr_api.php?fromDate=2019-03-01&toDate=2019-03-31";
        
        curl_setopt($ch, CURLOPT_URL,$url);     
        // Receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $ccmsdata = curl_exec($ch);
        curl_close ($ch);
        // Further processing ...
        
        $data=json_decode($ccmsdata);
        
        $leavetype="";
        $NoOfDays="";
        $paid=0;
        $fromDate="";
        $toDate="";
        
        if($data->status=='1' && !empty($data->attendance_data)){
            $leavesdata=$data->attendance_data;
            $i=1;
            foreach($leavesdata as $empid => $empleaves){
                foreach($empleaves as $k => $empleave){
                    //echo $empid."<br>";
                    $userdata=\App\Staffdetail::where('ccmsid', $empid)->first();
                    
                    if(!empty($userdata)){
                        $user_id=$userdata->user_id;

                        if($empleave->LeaveType!=null ){
                            switch ($empleave->LeaveType){
                            case 'Sick':
                                $leavetype="SL";
                                break;
                            case 'Casual':
                                $leavetype="CL";
                                break;
                            case 'Other':
                                $leavetype="OL";
                                break;
                            }
                        }else{
                            $leave="SL1";
                        }
                        
                        $NoOfDays=$empleave->NoOfDays;
                        $paid=$empleave->paid;
                        $fromDate=$empleave->fromDate;
                        $toDate=$empleave->toDate;
                        if($NoOfDays == 1){
                            
                            
                            $dated=date_create($empleave->fromDate);
                            $dtformat = date_format($dated,"Y-m-d");
                            if($leavetype=='CL'){
                                //echo $i.'='.$dtformat.'='.$user_id.'='.$leavetype.'='.$empleave->LeaveReason.'<br>';
                                echo "UPDATE leaves SET leavetype = 'CL' WHERE dated='".$dtformat."' and user_id=$user_id";
                                //echo "UPDATE attendancesheets SET status = '".$leavetype."' WHERE dated='".$dtformat."' and user_id=$user_id";                                
                                echo ";<br>";

                                $i++;
                            }
                            
                        }else{
                            //echo $empid."<br>";
                            //echo "<hr>".$empleave->fromDate."<br>";
                            //echo $empleave->toDate."<br>";
                            $begin=new DateTime(date('Y-m-d', strtotime($empleave->fromDate)));
                            $end=new DateTime(date('Y-m-d', strtotime($empleave->toDate)));      
                            $end = $end->modify( '+1 day' );             
                            $interval = new DateInterval('P1D');
                            $daterange = new DatePeriod($begin, $interval ,$end);
                            foreach($daterange as $dt){
                                //echo "dated= ".$dt->format('Y-m-d')."<br>";
                                $dated=date_create($dt->format('Y-m-d'));
                                $dtformat = date_format($dated,"Y-m-d");
                                if($leavetype=='CL'){
                                    //echo $i.'='.$dtformat.'='.$user_id.'='.$leavetype.'='.$empleave->LeaveReason.'<br>';
                                    echo "UPDATE leaves SET leavetype = 'CL' WHERE dated='".$dtformat."' and user_id=$user_id";
                                    //echo "UPDATE attendancesheets SET status = '".$leavetype."' WHERE dated='".$dtformat."' and user_id=$user_id";
                                    echo ";<br>";
                                    $i++;
                                }
                            }
                            //echo "<hr>";
                        }
                    }
                }
            }
        }//End if

    }
}
