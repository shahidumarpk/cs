@extends('layouts.mainlayout')
@section('content')
@if(session('success'))
    <script>
      $( document ).ready(function() {
        swal("Success", "{{session('success')}}", "success");
      });
      
    </script>
@endif

    <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Edit Role</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div id="kv-avatar-errors-1" class="center-block" style="width:800px;display:none"></div>
            <form class="form-horizontal" action="{!! url('/roles/1/edit'); !!}" method="get" enctype="multipart/form-data">
            @csrf
            <div class="box-body">
                

                <!--Roles -->
                

                <div class="form-group col-sm-12">

                    <label for="role_title">Role Title*</label>

                    <input id="role_title" name="role_title" type="text" class="form-control" placeholder="Enter Role Title" value="Super Admin" required="">

                </div>
                
                <div class="form-group col-sm-12">

                    <label for="role_title">Description</label>

                    <input id="role_title" name="role_title" type="text" class="form-control" placeholder="Enter Role Title" value="This is role description">

                </div>


                <div class="form-group  col-sm-12">

                    <label for="confirm_password">Assign Permissions*</label>

                </div>

                <div class="form-group col-sm-12">

                    
                    <label>
                    <input type="checkbox" id="menu_1" name="role_arr[]" checked="" value="1" onclick="checkbox_item(this.id);">
                    Product</label>
                    <br>
                    <div class="sub_section">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                    <input class="child_menu_1" type="checkbox" id="child_menu_2" name="role_arr[]" value="2" checked="">
                    Add Product</label>
                    </div>
                    <br>
                    <div class="sub_section">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                    <input class="child_menu_1" type="checkbox" id="child_menu_3" name="role_arr[]" value="3" checked="">
                    Manage Product</label>
                    </div>
                    <br>
                    <div class="sub_section">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                    <input class="child_menu_1" type="checkbox" id="child_menu_4" name="role_arr[]" value="4" checked="">
                    Edit Product</label>
                    </div>
                    <br>
                    <div class="sub_section">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                    <input class="child_menu_1" type="checkbox" id="child_menu_5" name="role_arr[]" value="5" checked="">
                    Delte Product</label>
                    </div>
                    <br>
                
                
                    <div class="sub_section">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                    <input class="child_menu_1" type="checkbox" id="child_menu_82" name="role_arr[]" value="82" checked="">
                    Product Detail</label>
                    </div>
                    <br>
                
                    <div class="sub_section">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                    <input class="child_menu_1" type="checkbox" id="child_menu_84" name="role_arr[]" value="84" checked="">
                    Delete Product</label>
                    </div>
                    <br>
                    
                
                
                
                
                    <br>

                    <label>
                    <input type="checkbox" id="menu_6" name="role_arr[]" checked="" value="6" onclick="checkbox_item(this.id);">
                    Categories</label>
                    <br>
                    <div class="sub_section">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                    <input class="child_menu_6" type="checkbox" id="child_menu_9" name="role_arr[]" value="9" checked="">
                    Edit category</label>
                    </div>
                    <br>
                    <div class="sub_section">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <label>
                    <input class="child_menu_6" type="checkbox" id="child_menu_10" name="role_arr[]" value="10" checked="">
                    Delete category</label>
                    </div>
                
                    </div>
                    <div class="clear"></div>
                
                <div class="form-group col-sm-12">
                    <label for="standard-list1">Status</label>
                    <select class="form-control" id="status" name="status">
                        <option value="1">Active</option>
                        <option value="0">InActive</option>
                    </select>
                </div>
                <div class="clear"></div>
                <hr>
                <div class="clear"></div>
                
                <input type="hidden" name="role_id" id="role_id" value="3" readonly="">

                    
                <!-- Roles -->
           

            </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <a href="{!! url('/roles'); !!}" class="btn btn-default">Cancel</a>
                <button type="submit" class="btn btn-info pull-right">Update</button>
              </div>
              <!-- /.box-footer -->
            </form>
</div>
<script>
$(function () {
    $('.button-checkbox').each(function () {

        // Settings
        var $widget = $(this),
            $button = $widget.find('button'),
            $checkbox = $widget.find('input:checkbox'),
            color = $button.data('color'),
            settings = {
                on: {
                    icon: 'glyphicon glyphicon-check'
                },
                off: {
                    icon: 'glyphicon glyphicon-unchecked'
                }
            };

        // Event Handlers
        $button.on('click', function () {
            $checkbox.prop('checked', !$checkbox.is(':checked'));
            $checkbox.triggerHandler('change');
            updateDisplay();
        });
        $checkbox.on('change', function () {
            updateDisplay();
        });

        // Actions
        function updateDisplay() {
            var isChecked = $checkbox.is(':checked');

            // Set the button's state
            $button.data('state', (isChecked) ? "on" : "off");

            // Set the button's icon
            $button.find('.state-icon')
                .removeClass()
                .addClass('state-icon ' + settings[$button.data('state')].icon);

            // Update the button's color
            if (isChecked) {
                $button
                    .removeClass('btn-default')
                    .addClass('btn-' + color + ' active');
            }
            else {
                $button
                    .removeClass('btn-' + color + ' active')
                    .addClass('btn-default');
            }
        }

        // Initialization
        function init() {

            updateDisplay();

            // Inject the icon if applicable
            if ($button.find('.state-icon').length == 0) {
                $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
            }
        }
        init();
    });
});
</script>
@endsection