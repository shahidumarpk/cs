@extends('layouts.mainlayout')
@section('content')
<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Manage Admins Roles</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="display responsive nowrap" style="width:100%">
                <thead>
                <tr>
                  <th>Role Name</th>
                  <th>Role Description</th>
                  <th>Created at</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                  for ($x = 0; $x <= 10; $x++) {
                ?>
                  <tr>
                    <td>Supper Admin</td>
                    <td>This is a super admin roles</td>
                    <td>14-June-2018</td>
                    <td><span class="btn btn-success">Active</span></td>
                    <td>
                      <a class="btn btn-success" title="Edit"  href="{!! url('/roles/1/edit') !!}"><i class="fa fa-edit"></i> </a>
                      <a class="btn btn-danger" title="Delete"><i class="fa fa-trash"></i> </a>
                      <a class="btn btn-info" title="Active"><i class="fa fa-check"></i> </a>
                      <a class="btn btn-warning" title="Deactivate"><i class="fa fa-times"></i> </a>
                    </td>
                  </tr>
                  <tr>
                    <td>Sales</td>
                    <td>This is a Sales Roles, only sales user can access here.</td>
                    <td>14-June-2018</td>
                    <td><span class="btn btn-danger">Deactive</span></td>
                    <td>
                      <a class="btn btn-success" title="Edit" href="{!! url('/roles/1/edit') !!}"><i class="fa fa-edit"></i> </a>
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
                  <th>Role Name</th>
                  <th>Role Description</th>
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