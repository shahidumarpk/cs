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
              <h3 class="box-title">Manage Categories</h3>
              <span class="pull-right">
              <a href="{!! url('/categories/create'); !!}" class="btn btn-info"><span class="fa fa-plus"></span> Add Category</a>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
            <table id="example1" class="display responsive nowrap" style="width:100%">
                <thead>
                <tr>
                  <th>Category Id</th>
                  <th>Name</th>
                  <th>Parent Category</th>
                  <th>Created by</th>
                  <th>Created at</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  for ($x = 1; $x <= 20; $x++) {
                ?>
                  <tr>
                    <td>{{ $x }}</td>
                    <td>LED TVs</td>
                    <td>Electronics</td>
                    <td>Shahid Umar</td>
                    <td>14-June-2018</td>
                    <td><span class="btn btn-success">Active</span></td>
                    <td>
                      <a class="btn btn-success" title="Edit" href="{!! url('/categories/'.$x.'/edit'); !!}"><i class="fa fa-edit"></i> </a>
                      <a class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i> </a>
                      <a class="btn btn-info" title="Active"><i class="fa fa-check"></i> </a>
                      <a class="btn btn-warning" title="Deactivate"><i class="fa fa-times"></i> </a>
                    </td>
                  </tr>
                <?php
                  }
                ?>
                </tbody>
                <tfoot>
                <tr>
                  <th>Category Id</th>
                  <th>Name</th>
                  <th>Parent Category</th>
                  <th>Created by</th>
                  <th>Created at</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->   

@endsection