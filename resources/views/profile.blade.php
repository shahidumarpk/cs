@extends('layouts.mainlayout')
@section('content')
@if(session('success'))
    <script>
      $( document ).ready(function() {
        swal("Success", "{{session('success')}}", "success");
      });
      
    </script>
@endif
<?php
$user = Auth::user();
?>
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
  <h3 class="box-title">Profile</h3>
</div>
<!-- /.box-header -->
<!-- form start -->
<div id="kv-avatar-errors-1" class="center-block" style="width:800px;display:none"></div>
<form class="form-horizontal" action="{{action('UserController@update', $user->id)}}" method="post" enctype="multipart/form-data">
@csrf
<input name="_method" type="hidden" value="PATCH">
<input name="profile" type="hidden" value="1">
<div class="box-body" >
<div class="row">
  <div class="col-md-4 text-center">
      <div class="kv-avatar">
          <div class="file-loading">
              <input id="avatar-1" name="avatar-1" type="file">
          </div>
      </div>
      <div class="kv-avatar-hint"><small>Select file < 1000 KB</small></div>
  </div> 
  <div class="col-md-8">
    <div class="form-group">
      <label for="fname" class="col-sm-3 control-label">First Name</label>

      <div class="col-sm-9">
        <input type="text" class="form-control" id="fname" name="fname" placeholder="First Name" autocomplete="off" value="{{ $user->fname }}" require >
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
        <input type="text" class="form-control" id="lname" name="lname" placeholder="Last Name" value="{{ $user->lname }}" autocomplete="off" require>
        @if ($errors->has('lname'))
              <span class="text-red">
                  <strong>{{ $errors->first('lname') }}</strong>
              </span>
          @endif
      </div>
    </div>

    <!--<div class="form-group">
      <label for="email" class="col-sm-3 control-label">Email</label>

      <div class="col-sm-9">
        <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ $user->email }}" autocomplete="off" require>
        @if ($errors->has('email'))
              <span class="text-red">
                  <strong>{{ $errors->first('email') }}</strong>
              </span>
          @endif
      </div>
    </div>-->


    <div class="form-group">
      <label for="phonenumber" class="col-sm-3 control-label">Phone Number</label>

      <div class="col-sm-9">
        <input type="text" class="form-control" id="phonenumber" name="phonenumber" placeholder="Phone Number" value="{{ $user->phonenumber }}" autocomplete="off" require>
        @if ($errors->has('phonenumber'))
              <span class="text-red">
                  <strong>{{ $errors->first('phonenumber') }}</strong>
              </span>
          @endif
      </div>
    </div>

  </div>
  </div>

</div>
  <!-- /.box-body -->
  <div class="box-footer">
    <a href="{!! url('/admins'); !!}" class="btn btn-default">Cancel</a>
    <button type="submit" class="btn btn-info pull-right">Update</button>
  </div>
  <!-- /.box-footer -->
</form>
</div>










<style>
    .loading{
        display: hidden;
        position: fixed;
        left: 0;
        top: 0;
        padding-top: 45vh;
        padding-left:100vh;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background-color: gray;
        opacity: 0.8;
    }
    .loader {
      border: 16px solid #f3f3f3; /* Light grey */
      border-top: 16px solid #3498db; /* Blue */
      border-radius: 50%;
      width: 120px;
      height: 120px;
      animation: spin 2s linear infinite;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    </style>

<script>
$(document).ready(function (e) {
  $(".loading").fadeOut();
  //Attendance Filter begins
  $('#filterDept').click( function() {
    var url;
    if($('#srchmonth').val()!=""){
      url="{{url('profile')}}/?srchmonth="+$('#srchmonth').val();
    }else{
      url ="{{url('profile')}}";
    }
    window.location.href =url;
  });

  
  });


</script>



@endsection