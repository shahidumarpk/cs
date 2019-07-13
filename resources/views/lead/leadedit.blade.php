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
              <h3 class="box-title">Edit Lead  -  {{$edit_lead->businessName}}</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div id="kv-avatar-errors-1" class="center-block" style="width:800px;display:none"></div>
            <form class="form-horizontal" action="{{ url('/lead/'.$edit_lead->id) }}" method="post" enctype="multipart/form-data">
            @csrf
			<!--<input name="_method" type="hidden" value="PATCH">-->
            <div class="box-body" >
            <div class="row">
              <div class="col-md-8">
				<!-- Lead Info -->
				<div class="form-group">
					  <label for="businessName" class="col-sm-3 control-label">Business Name</label>
					  <div class="col-sm-9">
						<input type="text" class="form-control" id="businessName" name="businessName" placeholder="businessName" autocomplete="off" value="{{ $edit_lead->businessName }}" require >
						@if ($errors->has('businessName'))
							  <span class="text-red">
								  <strong>{{ $errors->first('businessName') }}</strong>
							  </span>
						  @endif
					  </div>
				</div>	
				<div class="form-group">
					  <label for="businessNature" class="col-sm-3 control-label">Business Nature</label>
					  <div class="col-sm-9">
						<input type="text" class="form-control" id="businessNature" name="businessNature" placeholder="businessNature" autocomplete="off" value="{{ $edit_lead->businessNature }}" require >
						@if ($errors->has('businessNature'))
							  <span class="text-red">
								  <strong>{{ $errors->first('businessNature') }}</strong>
							  </span>
						@endif
					  </div>
				</div>
				<div class="form-group">
					  <label for="description" class="col-sm-3 control-label">Description</label>
					  <div class="col-sm-9">
						<textarea type="text" class="form-control" id="description" name="description" placeholder="description" autocomplete="off" value="" require >{{ $edit_lead->description }}</textarea>
						@if ($errors->has('description'))
							  <span class="text-red">
								  <strong>{{ $errors->first('description') }}</strong>
							  </span>
						  @endif
					  </div>
				</div>
				
				
				<!-- checkboxes -->
				<div class="form-group">
					  <label for="description" class="col-sm-3 control-label">Company Profile</label>
					  <div class="col-sm-9">
						<!--<input type="hidden" name="company_pro" value="0" />-->
						<input type="checkbox" class="minimal" name="company_pro" value="1" id="company_pro" <?php if($edit_lead->company_pro){ ?> checked="'checked'" <?php } ?>  />
						@if ($errors->has('company_pro'))
							  <span class="text-red">
								  <strong>{{ $errors->first('company_pro') }}</strong>
							  </span>
						  @endif
					  </div>
				</div>
				<div class="form-group">
					  <label for="description" class="col-sm-3 control-label">Testimonials</label>
					  <div class="col-sm-9">
						<!--<input type="hidden" name="testimonials" value="0" />-->
						<input type="checkbox" class="minimal" name="testimonials" value="1" id="testimonials" <?php if($edit_lead->testimonials){ ?> checked="'checked'" <?php } ?> />
						@if ($errors->has('testimonials'))
							  <span class="text-red">
								  <strong>{{ $errors->first('testimonials') }}</strong>
							  </span>
						  @endif
					  </div>
				</div>
				<div class="form-group">
					  <label for="description" class="col-sm-3 control-label">Solution & Services</label>
					  <div class="col-sm-9">
						<!--<input type="hidden" name="sol_ser" value="0" />-->
						<input type="checkbox" class="minimal" name="sol_ser" value="1" id="sol_ser" <?php if($edit_lead->sol_ser){ ?> checked="'checked'" <?php } ?> >
						@if ($errors->has('sol_ser'))
							  <span class="text-red">
								  <strong>{{ $errors->first('sol_ser') }}</strong>
							  </span>
						  @endif
					  </div>
				</div>
				
				<!-- Social links -->						
				<div class="form-group">
					  <label for="description" class="col-sm-3 control-label">Facebook</label>
					  <div class="col-sm-9">
						<input type="text" class="form-control" id="fb_link" name="fb_link" placeholder="Facebook link" autocomplete="off" value="{{ $edit_lead->fb_link }}"  />
						@if ($errors->has('fb_link'))
							  <span class="text-red">
								  <strong>{{ $errors->first('fb_link') }}</strong>
							  </span>
						  @endif
					  </div>
					  <label for="description" class="col-sm-3 control-label">Facebook Likes</label>
					  <div class="col-sm-3">
						<input type="number" class="form-control" id="fb_likes" name="fb_likes" placeholder="Likes count" autocomplete="off" value="{{ $edit_lead->fb_likes }}" />
						@if ($errors->has('fb_likes'))
							  <span class="text-red">
								  <strong>{{ $errors->first('fb_likes') }}</strong>
							  </span>
						  @endif
					  </div>
				</div>
				<div class="form-group">
					  <label for="description" class="col-sm-3 control-label">Twitter</label>
					  <div class="col-sm-9">
						<input type="text" class="form-control" id="tw_link" name="tw_link" placeholder="Twitter Link" autocomplete="off" value="{{ $edit_lead->tw_link }}"  />
						@if ($errors->has('tw_link'))
							  <span class="text-red">
								  <strong>{{ $errors->first('tw_link') }}</strong>
							  </span>
						  @endif
					  </div>
					  <label for="description" class="col-sm-3 control-label">Twitter Followers</label>
					  <div class="col-sm-3">
						<input type="number" class="form-control" id="tw_followers" name="tw_followers" placeholder="Followers count" autocomplete="off" value="{{ $edit_lead->tw_followers }}" />
						@if ($errors->has('tw_followers'))
							  <span class="text-red">
								  <strong>{{ $errors->first('tw_followers') }}</strong>
							  </span>
						  @endif
					  </div>
				</div>
				<div class="form-group">
					  <label for="description" class="col-sm-3 control-label">Instagram</label>
					  <div class="col-sm-9">
						<input type="text" class="form-control" id="in_link" name="in_link" placeholder="Instagram Link" autocomplete="off" value="{{ $edit_lead->in_link }}"  />
						@if ($errors->has('in_link'))
							  <span class="text-red">
								  <strong>{{ $errors->first('in_link') }}</strong>
							  </span>
						  @endif
					  </div>
					  <label for="description" class="col-sm-3 control-label">Instagram Followers</label>
					  <div class="col-sm-3">
						<input type="number" class="form-control" id="in_followers" name="in_followers" placeholder="Followers count" autocomplete="off" value="{{ $edit_lead->in_followers }}" />
						@if ($errors->has('in_followers'))
							  <span class="text-red">
								  <strong>{{ $errors->first('in_followers') }}</strong>
							  </span>
						  @endif
					  </div>
				</div>
				<div class="form-group">
					  <label for="description" class="col-sm-3 control-label">LinkedIn</label>
					  <div class="col-sm-9">
						<input type="text" class="form-control" id="li_link" name="li_link" placeholder="LinkedIn Link" autocomplete="off" value="{{ $edit_lead->li_link }}"  />
						@if ($errors->has('li_link'))
							  <span class="text-red">
								  <strong>{{ $errors->first('li_link') }}</strong>
							  </span>
						  @endif
					  </div>
					  <label for="description" class="col-sm-3 control-label">LinkedIn visitors</label>
					  <div class="col-sm-3">
						<input type="number" class="form-control" id="li_visitors" name="li_visitors" placeholder="Profile visitors" autocomplete="off" value="{{ $edit_lead->li_visitors }}" />
						@if ($errors->has('li_visitors'))
							  <span class="text-red">
								  <strong>{{ $errors->first('li_visitors') }}</strong>
							  </span>
						  @endif
					  </div>
				</div>
				<div class="form-group">
					  <label for="description" class="col-sm-3 control-label">Web</label>
					  <div class="col-sm-9">
						<input type="text" class="form-control" id="web_link" name="web_link" placeholder="web_link" autocomplete="off" value="{{ $edit_lead->web_link }}"  />
						@if ($errors->has('web_link'))
							  <span class="text-red">
								  <strong>{{ $errors->first('web_link') }}</strong>
							  </span>
						  @endif
					  </div>
				</div>				
				
				


                <div class="form-group">
                  <label for="status" class="col-sm-3 control-label">Status</label>

                  <div class="col-sm-9">
                    
                    <select name="leadstatus" class="form-control">
                        <option value="1" <?php echo ($edit_lead->leadstatus==1) ? "selected" : ""; ?>>Active</option>
                        <option value="2" <?php echo ($edit_lead->leadstatus==2) ? "selected" : ""; ?>>Deactivate</option>
                    </select>
                  </div>
                </div>


              </div>
              </div>

          </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a href="{!! url('/lead/leadview'); !!}" class="btn btn-default">Cancel</a>
                <button type="submit" class="btn btn-info pull-right">Update</button>
              </div>
              <!-- /.box-footer -->
            </form>
</div>
@endsection