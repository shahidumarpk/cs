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
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Manage Appointments</h3>
              <span class="pull-right">
              <!--<a href="{!! url('/appointment/appointmentcreate'); !!}" class="btn btn-info"><span class="fa fa-plus"></span> Add Appointment</a>-->

            </div>
            <!-- /.box-header -->
            <div class="box-body">
            @if(count($appointment) > 0)
              <table id="example1" class="display responsive nowrap" style="width:100%">
                <thead>
                <tr>
				  <th>Id</th>
                  <th>Description</th>
                  <th>Lead Id</th>
				  <th>User Id</th>
				  <th>Name</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($appointment as $appoint)
                  <tr>
					<td>{{$appoint['id']}}</td>
					<td>{{$appoint['description']}}</td>
					<td>{{$appoint['lead_id']}}</td>
					<td>{{$appoint['user_id']}}</td>
					<td>{{$appoint->user['fname']}} {{$appoint->user['lname']}}</td>
					
                     <!-- For Delete Form begin -->
                    <form id="form{{$appoint['id']}}" action="{{action('UserController@destroy_appointment', $appoint['id'])}}" method="post">
                        @csrf
                        <input name="_method" type="hidden" value="DELETE">
                    </form>
                    <!-- For Delete Form Ends -->
                    <td>
                      <!--<a href="{!! url('/lead/lead_detail/'.$appoint['id'] ); !!}" class="btn btn-primary" title="View Detail"><i class="fa fa-eye"></i> </a> -->
                      

					  <button class="btn btn-danger" onclick="archiveFunction('form{{$appoint['id']}}')"><i class="fa fa-trash"></i></button>
                    </td>
                  </tr>
                  @endforeach			  
                </tbody>
                <tfoot>
                <tr>
				  <th>Id</th>
                  <th>Description</th>
                  <th>Lead Id</th>
				  <th>User Id</th>
				  <th>Name</th>
                  <th>Action</th>
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

@endsection