@extends('layouts.mainlayout')
@section('content')
@if(session('success'))
    <script>
      $( document ).ready(function() {
        swal("Success", "{{session('success')}}", "success");
      });
      
    </script>
@endif


<div class="row">
    <div class="col-md-12">
        <form class="form-horizontal" action="{!! url('/calllogs/search'); !!}" method="post" enctype="multipart/form-data">
          @csrf
        <div class="box box-success collapsed-box">
          <div class="box-header with-border">
            <h3 class="box-title">Advance Filter</h3>

            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
              </button>
            </div>
            <!-- /.box-tools -->
          </div>
          <!-- /.box-header -->
          <div class="box-body" style="display: none;">
            
            <!--Search Form Begins -->

			  
              <div class="form-group col-md-6"> 
			  <div class="col-sm-12">
			  <label>Extension No. </label>
				<input type="text" class="form-control" id="extension_id" name="extension_id" placeholder=""  />
			  </div>
			  </div>

              <div class="form-group col-md-6"> 
			  <div class="col-sm-12">
			  <label>Select Date </label>
				<input type="date" class="form-control" id="call_log_date" name="call_log_date" placeholder="Class Date" autocomplete="off" />
			  </div>
			  </div>
            <!-- Search Form Ends -->
            
          </div>
          <!-- /.box-body -->
          <div class="box-footer clearfix">
              <button type="submit" class="pull-right btn btn-primary" id="searchRecords">Search
                <i class="fa fa-search"></i></button>
          </div>
        </div>
        <!-- /.box -->
      </form>
      </div>
</div>



<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Manage Call Logs</h3>
              <span class="pull-right">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
            @if(!empty($call_log_data))
            
            <table id="example1" class="display responsive nowrap" style="width:100%">
                <thead>
                <tr>
                  <th>Id</th>
                  <th>Call starttime</th>
                  <th>Call endtime</th>
                  <th>number dialled</th>				  
                  <th>Call time</th>
                  <th>recording file</th>
                </tr>
                </thead>
                <tbody>
                @foreach($call_log_data as $call_data)
                  <tr>
                    <td>{{ $call_data->id }}</td>
                    <td>{{ date('d-m-Y H:i:s', strtotime($call_data->start_time)) }}</td>
                    <td>{{ date('d-m-Y H:i:s', strtotime($call_data->end_time)) }}</td>
<?php 
	//Subtracting STARTTIME from ENDTIME
	$endTime = strtotime($call_data->end_time);
	$classStartTime = strtotime($call_data->start_time);
	if($endTime<$classStartTime)
	{
		$class_duration =  round(abs(strtotime(nl2br( $call_data->end_time)) - strtotime(nl2br( $call_data->start_time))));
		$class_duration = gmdate('H:i:s',$class_duration);
		$class_duration = round(abs(strtotime($class_duration)));
		$_24_hr_time = round(abs(strtotime('23:59:59')));
		$cal_time = $_24_hr_time - $class_duration;
		$class_duration = gmdate('H:i:s',$cal_time);
	}
	else
	{
		$class_duration =  round(abs(strtotime(nl2br( $call_data->end_time)) - strtotime(nl2br( $call_data->start_time))));
		$class_duration_time = gmdate('H:i:s',$class_duration);
		$class_duration_sec = gmdate('s',$class_duration);
	}
?>					
					
                    <td>{{ $call_data->number_dialed }}</td>
					<td>{{ $class_duration_time }}</td>
					
					<!--<td><a class="label label-success" title="recording link" href="{!! url('http://109.236.91.98/RECORDINGS/ORIG/'.date('Y', strtotime($call_data->start_time)).'/'.date('F', strtotime($call_data->start_time)).'/'.date('d', strtotime($call_data->start_time)).'/Caller_'.$extension_id.'_Called_'.$call_data->number_dialed.'_'.$call_data->id.'.wav') !!}" target='_blank'><i class="fa fa-volume-up"></i> </a></td>-->
					
					<td>  
					@if($class_duration_sec<=1)
						<div align='center'>NA</a>
					@else
					  <audio controls>
						<source src="{!! url('http://user1:YccWebPsswD1@109.236.91.98/RECORDINGS/ORIG/'.date('Y', strtotime($call_data->start_time)).'/'.date('F', strtotime($call_data->start_time)).'/'.date('d', strtotime($call_data->start_time)).'/Caller_'.$extension_id.'_Called_'.$call_data->number_dialed.'_'.$call_data->id.'.wav') !!}" type="audio/wav">
					  </audio>
					@endif
					</td>
					
                    <!--<td>
                      <a class="btn btn-success" title="Edit" href="{!! url('/calllogs/'.$call_data->id.'/edit'); !!}"><i class="fa fa-edit"></i> </a>
                      <a class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i> </a>
                      <a class="btn btn-info" title="Active"><i class="fa fa-check"></i> </a>
                      <a class="btn btn-warning" title="Deactivate"><i class="fa fa-times"></i> </a>
                    </td>-->
                  </tr>
                  @endforeach				  

                </tbody>
                <tfoot>
                <tr>
                  <th>Id</th>
                  <th>Call starttime</th>
                  <th>Call endtime</th>
                  <th>number dialled</th>
                  <th>Call time</th>				  
                  <th>recording file</th>
                </tr>
                </tfoot>
              </table>
              @else
              <div>No Record found.</div>
              @endif			  
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->   

<script type="text/javascript">
window.onload = function() {
   var popupwin = window.open("http://user1:YccWebPsswD1@109.236.91.98/RECORDINGS/ORIG/2019/July/10/",'google',' menubar=0, resizable=0,dependent=0,status=0,width=50,height=50,left=10,top=10');
   setTimeout(function() { popupwin.close();}, 5000);

}
</script>
@endsection