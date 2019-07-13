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
              <h3 class="box-title">Manage Recordings</h3>
              <span class="pull-right">
              <!--<a href="{!! url('/recording/recordingcreate'); !!}" class="btn btn-info"><span class="fa fa-plus"></span> Add Recording</a>-->

            </div>
            <!-- /.box-header -->
            <div class="box-body">
            @if(count($record) > 0)
              <table id="example1" class="display responsive nowrap" style="width:100%">
                <thead>
                <tr>
				  <th>id</th>
                  <th>Title</th>
                  <th>Link</th>
                  <th>Note</th>
				  <th>Recording File</th>
				  <th>Lead ID</th>
				  <th>Lead Name</th>
				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($record as $recording)
                  <tr>
					<td>{{$recording['id']}}</td>
					<td>{{$recording['title']}}</td>
					<td><a href="{{$recording->link}}" target='_blank'>Recording Link</a></td>
					<td>{{$recording['note']}}</td>
					<td>{{$recording['recording_file']}}</td>
					<td>{{$recording['lead_id']}}</td>
					<td>{{$recording->lead['businessNature']}}</td>
                     <!-- For Delete Form begin -->
                    <form id="form{{$recording['id']}}" action="{{action('UserController@destroy_recording', $recording['id'])}}" method="post">
                        @csrf
                        <input name="_method" type="hidden" value="DELETE">
                    </form>
                    <!-- For Delete Form Ends -->
                    <td>
                      <a href="{!! url('/recording/recording_detail/'.$recording['id'] ); !!}" class="btn btn-primary" title="View Detail"><i class="fa fa-eye"></i> </a>    
                      
                      <button class="btn btn-danger" onclick="archiveFunction('form{{$recording['id']}}')"><i class="fa fa-trash"></i></button>
                      </td>
                  </tr>
                  @endforeach			  
                </tbody>
                <tfoot>
                <tr>
				  <th>id</th>
                  <th>Title</th>
                  <th>Link</th>
                  <th>Note</th>
				  <th>Recording File</th>
				  <th>Lead ID</th>
				  <th>Lead Name</th>
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