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
              <h3 class="box-title">Manage Call Logs</h3>
              <span class="pull-right">
              <a href="{!! url('/calllogs/create'); !!}" class="btn btn-primary"><span class="fa fa-plus"></span> Add Call Logs</a>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
            @if(count($calllogs) > 0)				
            <table id="example1" class="display responsive nowrap" style="width:100%">
                <thead>
                <tr>
                  <th>Id</th>
                  <th>Source caller</th>
                  <th>destination caller</th>
                  <th>Call starttime</th>
                  <th>Call endtime</th>
				  <th>recording file</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($calllogs as $calllog)
                  <tr>
                    <td>{{ $calllog['id'] }}</td>
                    <td>{{ $calllog['source_caller'] }}</td>
                    <td>{{ $calllog['destination_caller'] }}</td>
                    <td> {{ date('D-M-Y H:i:s', strtotime($calllog['call_start_time'])) }}</td>
                    <td>{{ $calllog['call_end_time'] }}</td>
                    <td>{{ $calllog['recording_file'] }}</td>
                    <td>{{ $calllog['status'] }}</td>
                    <td>
                      <a class="btn btn-success" title="Edit" href="{!! url('/calllogs/'.$calllog['id'].'/edit'); !!}"><i class="fa fa-edit"></i> </a>
                      <a class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i> </a>
                      <a class="btn btn-info" title="Active"><i class="fa fa-check"></i> </a>
                      <a class="btn btn-warning" title="Deactivate"><i class="fa fa-times"></i> </a>
                    </td>
                  </tr>
                  @endforeach				  

                </tbody>
                <tfoot>
                <tr>
                  <th>Id</th>
                  <th>Source caller</th>
                  <th>destination caller</th>
                  <th>Call starttime</th>
                  <th>Call endtime</th>
				  <th>recording file</th>
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