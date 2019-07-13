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
              <h3 class="box-title">{{$user->fname}} {{$user->lname}} Details</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body" >
            <div class="row">
              <div class="col-md-4 text-center">
                  <div class="kv-avatar">
                          <img src="{{ asset('img/staff/'.$user->avatar) }}" width="90%">
                  </div>
              </div> 
			  <input id='id' name='id' type='hidden' value='{{$user->id}}' />
              <div class="col-md-8">
              <table class="table table-striped">
                <tr>
                    <td><b>First Name</b></td>
                    <td>{{$user->fname}}</td>
                </tr>
                <tr>
                    <td><b>Last Name</b></td>
                    <td>{{$user->lname}}</td>
                </tr>
                <tr>
                    <td><b>Status</b></td>
                    <td>
                        @if ($user->status === 1)
                          <span class="text-green"><b>Active</b></span>
                        @else
                            <span class="text-red"><b>Deactive</b></span>
                        @endif
                    </td>
                </tr>
                
              </table>
                  

              </div>
              </div>

          </div>
              <!-- /.box-body -->
              
              <!-- /.box-footer -->
</div>






<!-- Attendance Logs begins -->
<div class="box box-danger">
    <div class="box-header with-border">
        <h3 class="box-title">Attendance</h3>
        <div class="box-tools pull-right">
            <input class="custom-input" type="month" name="srchmonth" id="srchmonth" autocomplete="off"  min="2019-01" max="{{date('Y-m')}}"  value="{{date('Y-m')}}" />
            <button class="btn btn-success" id="filterDept"><li class="fa fa-search"></li></button>
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body" style="">
            @if(count($attlog) > 0)
                <table id="attlogs" class="table table-border display responsive wrap" style="width:100%;">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Day</th>
                    <th>Check-In</th>
                    <th>Check-Out</th>
                    <th>Check-Out Marked</th>
                    <th>Tardies</th>
                    <th>Short Leave</th>
                    <th>Hours Worked</th>
                    <th>Remarks</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  $tardies=0;
                  $absents=0;
                  $shortleaves=0;
                  $upleaves=0;
                  $deducteddays=0;
                  $unpaiddays=0;
                  $totaldays=0;
                ?>
                @foreach($attlog as $att)
                    <?php
                        if($att->dayname=='Sun'){
                            $class="bg-green";
                          if($att->status=='-'){  
                            $unpaiddays++;
                          }
                        }elseif($att->status=='Holiday'){
                            $class="bg-green";
                        }elseif($att->status=='X'){
                            $class="bg-red";
                            $absents++;
                        }elseif($att->status=='UL'){
                          $class="";
                          $upleaves++;
                        }elseif($att->status=='-'){
                          $class="";
                          $unpaiddays++;
                        }else{
                            $class="";
                        }
                        $totaldays++;
                        $tardies+=$att->tardies;
                        $shortleaves+=$att->shortleaves;
                    ?>
                    <tr class="{{$class}}">
                    <td>{{$att->dated->format('d-m-Y')}}</td>
                    <td>{{$att->dayname}}</td>
                    <td>{{$att->checkin}}</td>
                    <td>{{$att->checkout}}</td>
                    <td>{{$att->checkoutfound}}</td>
                    <td>{{$att->tardies}}</td>
                    <td>{{$att->shortleaves}}</td>
                    <td>{{$att->workedhours}}</td>
                    <td>{{$att->remarks}}</td>
                    <td>{{$att->status}}</td>
                    </tr>
                    @endforeach			  
                </tbody>
                <tfoot>
                </tfoot>
                </table>

                @else
                <div>No Record found.</div>
                @endif

    </div>
    <!-- /.box-body -->
    <div class="box-footer clearfix" style="">
    </div>
    <!-- /.box-footer -->
</div>
<!-- Attendance Logs ends -->



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
	var id = $('#id').val()
    if($('#srchmonth').val()!=""){
      url="{{url('admins')}}/"+id+"/?srchmonth="+$('#srchmonth').val();
    }else{
      url ="{{url('admins')}}";
    }
    window.location.href =url;
  });

  
  });


</script>








@endsection