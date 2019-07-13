<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'NSOL | Admin Console') }}</title>

  @include('layouts.partials.headerscripts')
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
<!-- Include header bar -->
 @include('layouts.partials.topnav')
<!-- Include left side bar -->
@include('layouts.partials.leftnav')


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
<!-- Include content header -->
  @include('layouts.partials.contentheader')

    <!-- Main content -->
    <section class="content container-fluid">    
        @yield('content')
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Include main footer -->
  @include('layouts.partials.footer')

  <!-- Include Right sidebar for setting if required -->
  @include('layouts.partials.rightsidebar')

</div>
<!-- ./wrapper -->

<!-- Include Footer scripts -->
@include('layouts.partials.footerscripts')

</body>
</html>