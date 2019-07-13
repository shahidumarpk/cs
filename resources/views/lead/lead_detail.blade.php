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
              <h3 class="box-title">Details of <b>{{$lead_detail->businessName}}</b></h3>
            </div>
            <!-- /.box-header -->
				<div class="box-body" >
				  <div class="row">
					  <div class="col-md-8">
					  <table class="table table-striped">
						<tr>
							<td><b>Business Name</b></td>
							<td>{{$lead_detail->businessName}}</td>
						</tr>
						<tr>
							<td><b>Business Nature</b></td>
							<td>{{$lead_detail->businessNature}}</td>
						</tr>
						<tr>
							<td><b>Description</b></td>
							<td>{{$lead_detail->description}}</td>
						</tr>
						<!-- checkboxes -->
						<tr>
							<td><b>Company Profile</b></td>
							<td><input type="checkbox" class="minimal" name="company_pro" value="1" id="company_pro" <?php if($lead_detail->company_pro){ ?> checked="'checked'" <?php } ?>  disabled /></td>
						</tr>
						<tr>
							<td><b>Testimonials</b></td>
							<td><input type="checkbox" class="minimal" name="testimonials" value="1" id="testimonials" <?php if($lead_detail->testimonials){ ?> checked="'checked'" <?php } ?> disabled /></td>
						</tr>
						<tr>
							<td><b>Solution & Services</b></td>
							<td><input type="checkbox" class="minimal" name="sol_ser" value="1" id="sol_ser" <?php if($lead_detail->sol_ser){ ?> checked="'checked'" <?php } ?> disabled ></td>
						</tr>

						<!-- social links -->
						<tr>
							<td><b>Facebook</b></td>
							<td>{{$lead_detail->fb_link}}</td>
							<td>{{$lead_detail->fb_likes}}</td>
						</tr>
						<tr>
							<td><b>Twitter</b></td>
							<td>{{$lead_detail->tw_link}}</td>
							<td>{{$lead_detail->tw_followers}}</td>
						</tr>				
						<tr>
							<td><b>Instagram</b></td>
							<td>{{$lead_detail->in_link}}</td>
							<td>{{$lead_detail->in_followers}}</td>
						</tr>
						<tr>
							<td><b>LinkedIn</b></td>
							<td>{{$lead_detail->li_link}}</td>
							<td>{{$lead_detail->li_visitors}}</td>
						</tr>
						<tr>
							<td><b>Web</b></td>
							<td>{{$lead_detail->web_link}}</td>
						</tr>

						<tr>
							<td><b>Created At</b></td>
							<td>{{$lead_detail->created_at->format('d-m-Y')}}</td>
						</tr>
						<tr>
							<td><b>Updated At</b></td>
							<td>{{$lead_detail->updated_at->format('d-m-Y')}}</td>
						</tr>				
						
						<tr>
							<td><b>Status</b></td>
							<td>
								@if ($lead_detail->leadstatus === 1)
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
			
			<!-- Recording table START -->
            <!-- /.box-header -->
            <div class="box-header with-border">
              <h3 class="box-title">Recordings of Above Lead [{{$lead_detail->businessName}}]</b></h3>
            </div>			
            <div class="box-body">
				<div class="row">
				<div class="col-md-8">
            @if(count($recordings) > 0)
              <table id="example1" class="display responsive nowrap" style="width:100%" border='1'>
                <thead>
                <tr>
				  <th>id</th>
                  <th>Title</th>
                  <th>Link</th>
                  <th>Note</th>
				  <th>Recording File</th>
				  <th>Lead ID</th>
				  
                </tr>
                </thead>
                <tbody>
                @foreach($recordings as $recording)
                  <tr>
					<td>{{$recording->id}}</td>
					<td>{{$recording->title}}</td>
					<td><a href="{{$recording->link}}" target='_blank'>Recording Link</a></td>
					<td>{{$recording->note}}</td>
					<td>{{$recording->recording_file}}</td>
					<td>{{$recording->lead_id}}</td>
					
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

                </tr>
                </tfoot>
              </table>
              @else
              <div>No Record found.</div>
              @endif
			  	</div>
				</div>
            </div>
            <!-- /.box-body -->
			<!-- Recording table END -->
			  
			  
			  
              <div class="box-footer">
                <a href="{!! url('/admins'); !!}" class="btn btn-default">Back</a>
              </div>
              <!-- /.box-footer -->
</div>
@endsection