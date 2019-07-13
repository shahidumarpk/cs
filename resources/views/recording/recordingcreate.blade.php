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
              <h3 class="box-title">Add Recording</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div id="kv-avatar-errors-1" class="center-block" style="width:800px;display:none"></div>
            <form class="form-horizontal" action="{!! url('/recording'); !!}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="box-body" >
            <div class="row">
				<!--lead_id against which recording will be stored -->
				<input name='lead_id' type='hidden' value='<?php echo $lead_id; ?>' />
              <div class="col-md-8">
                <div class="form-group">
                  <label for="title" class="col-sm-3 control-label">title:</label>

                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="title" name="title" placeholder="title" autocomplete="off" value="" require >
                    @if ($errors->has('title'))
                          <span class="text-red">
                              <strong>{{ $errors->first('title') }}</strong>
                          </span>
                      @endif
                  </div>
                </div>
                <div class="form-group">
                  <label for="link" class="col-sm-3 control-label">link:</label>

                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="link" name="link" placeholder="link" value="" autocomplete="off" require>
                    @if ($errors->has('link'))
                          <span class="text-red">
                              <strong>{{ $errors->first('link') }}</strong>
                          </span>
                      @endif
                  </div>
                </div>
		
				<div class="form-group">
					  <label for="note" class="col-sm-3 control-label">Note:</label>

					  <div class="col-sm-9">
						<textarea type="text" class="form-control" id="note" name="note" placeholder="note" autocomplete="off" value="" require ></textarea>
						@if ($errors->has('note'))
							  <span class="text-red">
								  <strong>{{ $errors->first('note') }}</strong>
							  </span>
						  @endif
					  </div>
				</div>
				
				<div class="form-group">
					  <label for="recording_file" class="col-sm-3 control-label">Select file to upload[mp3 Only]:</label>

					  <div class="col-sm-9">
						<input type="hidden" value="{{ csrf_token() }}" name="_token">
						<input class='form-control' type="file" name="recording_file" id="recording_file">						
						@if ($errors->has('recording_file'))
							  <span class="text-red">
								  <strong>{{ $errors->first('recording_file') }}</strong>
							  </span>
						  @endif
					  </div>
				</div>
              </div>
            </div>

          </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a href="{!! url('/lead/leadshow'); !!}" class="btn btn-default">Cancel</a>
                <button type="submit" class="btn btn-info pull-right">Add</button>
              </div>
              <!-- /.box-footer -->
            </form>
</div>
@endsection