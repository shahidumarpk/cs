<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\CallLogs;

class CalllogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

		$extension_id = $request->get('extension_id');
		$call_log_date = $request->get('call_log_date');
		$call_log_data = array();
        //
		if($extension_id!='' && $call_log_date){
		$username = "user1";
		$password = "YccWebPsswD1";		
		//$calllogs = \App\CallLogs::all();
             $ch = curl_init();
            $url="http://109.236.91.98/vicidialx/call_log_api.php";
			$qrystring="?extension=$extension_id&fromDate=$call_log_date&toDate=$call_log_date";
			curl_setopt($ch, CURLOPT_URL,$url.$qrystring);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);			
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);			
			//curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");
            $call_logs = curl_exec($ch);
			//curl_getinfo($ch);
//get http code
$HTTP_Code = curl_getinfo($ch, CURLINFO_HTTP_CODE);			
            curl_close ($ch);
			//dd($HTTP_Code);
			//echo 'HTTP code: ' . $HTTP_Code;
			
            // Further processing ...
            $call_log_data=json_decode($call_logs);
			$call_log_data = $call_log_data->call_log_info; 
			

            $ch_url = curl_init();
//			http://user1:YccWebPsswD1@109.236.91.98/RECORDINGS/ORIG/2019/July/10/		//working
            $url="http://'user1':'YccWebPsswD1'@109.236.91.98/RECORDINGS/ORIG/2019/July/10/";
			//$qrystring="?extension=$extension_id&fromDate=$call_log_date&toDate=$call_log_date";
			curl_setopt($ch_url, CURLOPT_URL,$url);	
			curl_setopt($ch_url, CURLOPT_HEADER, true);			
			curl_setopt($ch_url, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
curl_setopt($ch_url, CURLOPT_RETURNTRANSFER, 1);
//We don't want any HTTPS / SSL errors.
curl_setopt($ch_url, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch_url, CURLOPT_SSL_VERIFYPEER, false); 			
			//curl_setopt($ch_url, CURLOPT_USERPWD, "$username:$password");
            $call_logs_url = curl_exec($ch_url);
			//curl_getinfo($ch_url);
//get http code
$HTTP_Code_url = curl_getinfo($ch_url, CURLINFO_HTTP_CODE);			
            curl_close ($ch_url);
			//dd($HTTP_Code_url);
			//echo 'HTTP code: ' . $HTTP_Code_url;	
            $call_log_data_url=json_decode($call_logs_url);
			//$call_log_data_url = $call_log_data_url->call_log_info;	
//dd($call_log_data_url);			
		}
		else{
			//$call_log_data='';
		}
			//dd($call_log_data);
		return view('calllogs.calllogs',compact('call_log_data','call_log_date','extension_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
