<?php
$user = Auth::user();
?>
 <!-- Main Header -->
 <header class="main-header">
<!-- Logo -->
<a href="{!! url('/home'); !!}" class="logo">
  <!-- mini logo for sidebar mini 50x50 pixels -->
  <span class="logo-mini"><b>NSOL</b></span>
  <!-- logo for regular state and mobile devices -->
  <span class="logo-lg"><b>NSOL</b></span>
</a>

<!-- Header Navbar -->
<nav class="navbar navbar-static-top" role="navigation">
  <!-- Sidebar toggle button-->
  <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
    <span class="sr-only">Toggle navigation</span>
  </a>
  <!-- Navbar Right Menu -->
  <div class="navbar-custom-menu">
    <ul class="nav navbar-nav">
	
      
      
      
      <!-- User Account Menu -->
      <li class="dropdown user user-menu">
        <!-- Menu Toggle Button -->
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
          <!-- The user image in the navbar-->
          <img src="{{ asset('img/staff/'.$user->avatar) }}" class="user-image" alt="User Image">
          <!-- hidden-xs hides the username on small devices so only the image appears. -->
          <span class="hidden-xs">{{$user->fname}} {{$user->lname}}</span>
        </a>
        <ul class="dropdown-menu">
          <!-- The user image in the menu -->
          <li class="user-header">
            <img src="{{ asset('img/staff/'.$user->avatar) }}" class="img-circle" alt="User Image">

            <p>
              {{$user->fname}} {{$user->lname}}
            </p>
          </li>
          <li class="user-footer">
            <div>
              <a href="{!! url('/profile'); !!}" class="btn btn-primary btn-flat">Profile</a>
              <a href="{!! url('/changepassword'); !!}" class="btn btn-warning btn-flat">Change Password</a>
              <a class="btn btn-danger btn-flat" href="{{ route('logout') }}"
                  onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                  {{ __('Logout') }}
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
              </form>
            </div>
            
          </li>
        </ul>
      </li>
      
    </ul>
  </div>
</nav>
</header>