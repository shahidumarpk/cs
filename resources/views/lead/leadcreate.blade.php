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
              <h3 class="box-title">Add Customer And Lead</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div id="kv-avatar-errors-1" class="center-block" style="width:800px;display:none"></div>
            <form class="form-horizontal" action="{!! url('/lead'); !!}" method="post" enctype="multipart/form-data">
            @csrf
          <div class="box-body" >
			
            <div class="row">			
            <div class="box-header with-border">
              <h4 class="box-title">Customer Form</h4>
            </div>
			<!-- Customer Info -->	
              <div class="col-md-8">
                <div class="form-group">
                  <label for="fname" class="col-sm-3 control-label">First Name</label>

                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name" autocomplete="off" value="{{ old('fname') }}" require >
                    @if ($errors->has('fname'))
                          <span class="text-red">
                              <strong>{{ $errors->first('fname') }}</strong>
                          </span>
                      @endif
                  </div>
                </div>
                <div class="form-group">
                  <label for="lname" class="col-sm-3 control-label">Last Name</label>

                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name" value="{{ old('lname') }}" autocomplete="off" require>
                    @if ($errors->has('lname'))
                          <span class="text-red">
                              <strong>{{ $errors->first('lname') }}</strong>
                          </span>
                      @endif
                  </div>
                </div>

                <div class="form-group">
                  <label for="email" class="col-sm-3 control-label">Email</label>

                  <div class="col-sm-9">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email') }}" autocomplete="off" require>
                    @if ($errors->has('email'))
                          <span class="text-red">
                              <strong>{{ $errors->first('email') }}</strong>
                          </span>
                      @endif
                  </div>
                </div>

                <div class="form-group">
                  <label for="phonenumber" class="col-sm-3 control-label">Phone Number</label>

                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="phonenumber" name="phonenumber" placeholder="Phone Number" value="{{ old('phonenumber') }}" autocomplete="off" require>
                    @if ($errors->has('phonenumber'))
                          <span class="text-red">
                              <strong>{{ $errors->first('phonenumber') }}</strong>
                          </span>
                      @endif
                  </div>
                </div>
				
			<!-- Lead Info -->					
			<div class="box-header with-border">
              <h4 class="box-title">Lead Form</h4>
            </div> 
			<div class="form-group">
                  <label for="businessName" class="col-sm-3 control-label">Business Name</label>
                  <div class="col-sm-9">
                    <input type="text" class="form-control" id="businessName" name="businessName" placeholder="businessName" autocomplete="off" value="" require >
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
                    <input type="text" class="form-control" id="businessNature" name="businessNature" placeholder="businessNature" autocomplete="off" value="" require >
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
                    <textarea type="text" class="form-control" id="description" name="description" placeholder="description" autocomplete="off" value="" require ></textarea>
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
					<input type="checkbox" class="minimal" name="company_pro" value="1" id="company_pro" >
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
					<input type="checkbox" class="minimal" name="testimonials" value="1" id="testimonials" >
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
					<input type="checkbox" class="minimal" name="sol_ser" value="1" id="sol_ser" >
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
                    <input type="text" class="form-control" id="fb_link" name="fb_link" placeholder="Facebook link" autocomplete="off" value="" require />
                    @if ($errors->has('fb_link'))
                          <span class="text-red">
                              <strong>{{ $errors->first('fb_link') }}</strong>
                          </span>
                      @endif
                  </div>
				  <label for="description" class="col-sm-3 control-label">Facebook Likes</label>
                  <div class="col-sm-3">
                    <input type="number" class="form-control" id="fb_likes" name="fb_likes" placeholder="Likes count" autocomplete="off" value="" require />
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
                    <input type="text" class="form-control" id="tw_link" name="tw_link" placeholder="Twitter Link" autocomplete="off" value="" require />
                    @if ($errors->has('tw_link'))
                          <span class="text-red">
                              <strong>{{ $errors->first('tw_link') }}</strong>
                          </span>
                      @endif
                  </div>
				  <label for="description" class="col-sm-3 control-label">Twitter Followers</label>
                  <div class="col-sm-3">
                    <input type="number" class="form-control" id="tw_followers" name="tw_followers" placeholder="Followers count" autocomplete="off" value="" require />
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
                    <input type="text" class="form-control" id="in_link" name="in_link" placeholder="Instagram Link" autocomplete="off" value="" require />
                    @if ($errors->has('in_link'))
                          <span class="text-red">
                              <strong>{{ $errors->first('in_link') }}</strong>
                          </span>
                      @endif
                  </div>
				  <label for="description" class="col-sm-3 control-label">Instagram Followers</label>
                  <div class="col-sm-3">
                    <input type="number" class="form-control" id="in_followers" name="in_followers" placeholder="Followers count" autocomplete="off" value="" require />
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
                    <input type="text" class="form-control" id="li_link" name="li_link" placeholder="LinkedIn Link" autocomplete="off" value="" require />
                    @if ($errors->has('li_link'))
                          <span class="text-red">
                              <strong>{{ $errors->first('li_link') }}</strong>
                          </span>
                      @endif
                  </div>
				  <label for="description" class="col-sm-3 control-label">LinkedIn visitors</label>
                  <div class="col-sm-3">
                    <input type="number" class="form-control" id="li_visitors" name="li_visitors" placeholder="Profile visitors" autocomplete="off" value="" require />
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
                    <input type="text" class="form-control" id="web_link" name="web_link" placeholder="web_link" autocomplete="off" value="" require />
                    @if ($errors->has('web_link'))
                          <span class="text-red">
                              <strong>{{ $errors->first('web_link') }}</strong>
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