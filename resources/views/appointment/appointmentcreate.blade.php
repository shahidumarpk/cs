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
              <h3 class="box-title">Add Appointment for LEAD [{{$lead_info->businessName}}]</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div id="kv-avatar-errors-1" class="center-block" style="width:800px;display:none"></div>
            <form class="form-horizontal" action="{!! url('/appointment'); !!}" method="post" enctype="multipart/form-data">
            @csrf
          <div class="box-body" >
			
            <div class="row">			
				<!--lead_id against which recording will be stored -->
				<input name='lead_id' type='hidden' value='<?php echo $lead_info->id; ?>' />
				<input name='user_id' type='hidden' value='<?php echo $lead_info->user_id; ?>' />
			<!-- Customer Info -->	
              <div class="col-md-8">
				<div class="form-group">
					  <label for="description" class="col-sm-3 control-label">Description</label>
					  <div class="col-sm-9">
						<textarea type="text" class="form-control" id="description" name="description" placeholder="description" autocomplete="off" value="" require ></textarea>
						@if ($errors->has('description'))
							  <span class="text-red">
								  <strong>{{ $errors->first('description') }}</strong>
							  </span>
						  @endif
					  </div>
				</div>

              </div>
            </div>

          </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a href="{!! url('/appointment/appointmentview'); !!}" class="btn btn-default">Cancel</a>
                <button type="submit" class="btn btn-info pull-right">Add</button>
              </div>
              <!-- /.box-footer -->
            </form>
</div>
@endsection