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
              <h3 class="box-title">Manage Leads</h3>
              <span class="pull-right">
              <a href="{!! url('/lead/leadcreate'); !!}" class="btn btn-info"><span class="fa fa-plus"></span> Add Lead</a>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
            @if(count($leads) > 0)
              <table id="example1" class="display responsive nowrap" style="width:100%">
                <thead>
                <tr>
				  <th>User id</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone Number</th>
				  <th>Lead id</th>
				  <th>Business Name</th>
				  <th>Business Nature</th>
                  <th>Status</th>
				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($leads as $lead)
                  <tr>
					@if ($lead->user->is_customer===0)
						<?php $is_customer='Admin'; ?>
					@else 
						<?php $is_customer='Customer'; ?>
					@endif
                    <td>{{$lead->user->id }}</td>
                    <td>{{$lead->user->fname }} {{ $lead->user->lname }} [<b><?php echo $is_customer;?></b>]</td>
                    <td>{{$lead->user->email}}</td>
                    <td>{{$lead->user->phonenumber}}</td>
					<td>{{$lead['id']}}</td>
					<td>{{$lead['businessName']}}</td>
					<td>{{$lead['businessNature']}}</td>
                    <td>
                      @if ($lead['leadstatus'] === 1)
                      <span class="btn btn-success">Active</span>
                      @else
                      <span class="btn btn-danger">Deactive</span>
                      @endif
                    </td>
                     <!-- For Delete Form begin -->
                    <form id="form{{$lead['id']}}" action="{{action('UserController@destroy_lead', $lead['id'])}}" method="post">
                        @csrf
                        <input name="_method" type="hidden" value="DELETE">
                    </form>
                    <!-- For Delete Form Ends -->
                    <td>
                      <a href="{!! url('/lead/lead_detail/'.$lead['id'] ); !!}" class="btn btn-primary" title="View Detail"><i class="fa fa-eye"></i> </a>    
                      @can('update',$lead)
					  <a href="{!! url('/lead/'.$lead['id'].'/edit'); !!}"  class="btn btn-success" title="Edit"><i class="fa fa-edit"></i> </a>
                      @endcan
					  <a href="{!! url('/recording/'.$lead['id'].'/recordingcreate'); !!}" target='_blank'  class="btn btn-warning" title="Recording link"><i class="fa fa-file-audio-o"></i> </a>
                      <a href="{!! url('/appointment/'.$lead['id'].'/appointmentcreate'); !!}" target='_blank'  class="btn btn-primary" title="Make appointment"><i class="fa fa-calendar"></i> </a>
                      
					  @if ($lead['leadstatus'] === 1)
                        <a href="{!! url('/lead/deactivate_lead/'.$lead['id']); !!}"  class="btn btn-warning" title="Deactivate"><i class="fa fa-times"></i> </a>
                      @else
                        <a href="{!! url('/lead/active_lead/'.$lead['id']); !!}"  class="btn btn-info" title="Active"><i class="fa fa-check"></i> </a>
                      @endif
					  <button class="btn btn-danger" onclick="archiveFunction('form{{$lead['id']}}')"><i class="fa fa-trash"></i></button>
                    </td>
                  </tr>
                  @endforeach			  
                </tbody>
                <tfoot>
                <tr>
                  <th>User id</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone Number</th>
				  <th>Lead id</th>
				  <th>Business Name</th>
				  <th>Business Nature</th>
                  <th>Status</th>				  
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