@extends('layouts.mainlayout')
@section('content')
@if(session('success'))
    <script>
      $( document ).ready(function() {
        swal("Success", "{{session('success')}}", "success");
      });
      
    </script>
@endif
<!-- some CSS styling changes and overrides -->
<style>
.kv-avatar .krajee-default.file-preview-frame,.kv-avatar .krajee-default.file-preview-frame:hover {
    margin: 0;
    padding: 0;
    border: none;
    box-shadow: none;
    text-align: center;
}
.kv-avatar {
    display: inline-block;
}
.kv-avatar .file-input {
    display: table-cell;
    width: 213px;
}
.kv-reqd {
    color: red;
    font-family: monospace;
    font-weight: normal;
}
</style>

    <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Recording</b></h3>
            </div>
            <!-- /.box-header -->
				<div class="box-body" >
				  <div class="row">
					  <div class="col-md-8">
					  <table class="table table-striped">
						<tr>
							<td><b>Title</b></td>
							<td>{{$recording_detail->title}}</td>
						</tr>
						<tr>
							<td><b>Link</b></td>
							<td><a href="{{$recording_detail->link}}">Recording link</a></td>
						</tr>
						<tr>
							<td><b>Note</b></td>
							<td>{{$recording_detail->note}}</td>
						</tr>
						<tr>
							<td><b>Created</b></td>
							<td>{{$recording_detail->created_at}}</td>
						</tr>
						<tr>
							<td><b>Updated</b></td>
							<td>{{$recording_detail->updated_at}}</td>
						</tr>						
					  </table>
					  </div>
				  </div>
				</div>
              <!-- /.box-body -->
			
			  
			  
              <div class="box-footer">
                <a href="{!! url('/recording/recordingview'); !!}" class="btn btn-default">Back</a>
              </div>
              <!-- /.box-footer -->
</div>
@endsection