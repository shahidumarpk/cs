@extends('layouts.mainlayout')
@section('content')

	  
<div class="row">
	<div class="col-md-6 col-sm-6 col-xs-12">
		<!--<h2>Welcome, {{ Auth::user()->fname }} {{ Auth::user()->lname }}</h2>-->

	</div>
</div>
	  
	<div class="row">
	
        <div class="col-md-4 col-sm-6 col-xs-12">
		<a href="{!! url('/admins/326'); !!}" > 
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-user"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Agents Assigned</span>
              <span class="info-box-number">1</span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
		</a>
        </div>
        <!-- /.col -->	
	
      

	</div>

    </div>
 
@endsection