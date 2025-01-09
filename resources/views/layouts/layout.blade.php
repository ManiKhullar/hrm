<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from dleohr.dreamguystech.com/template-1/dleohr-horizontal/employees-dashboard.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 28 Aug 2023 13:43:06 GMT -->

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ Route::currentRouteName() }} add Page</title>
	<link rel="icon" type="image/x-icon" href="{{ asset('assets/img/bluethink_icon.ico') }}">
	<link rel="stylesheet" href="{{ asset('assets1/css/bootstrap.min.css') }}">

	<link rel="stylesheet" href="{{ asset('assets1/css/lnr-icon.css') }}">

	<link rel="stylesheet" href="{{ asset('assets1/css/font-awesome.min.css') }}">

	<link rel="stylesheet" href="{{ asset('assets1/css/bootstrap-datetimepicker.min.css') }}">

	<link rel="stylesheet" href="{{ asset('assets1/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">

	<link rel="stylesheet" href="{{ asset('assets1/plugins/select2/select2.min.css') }}">

	<link rel="stylesheet" href="{{ asset('assets/css/fullcalendar.css') }}">

	<link rel="stylesheet" href="{{ asset('assets1/css/style.css') }}">

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
	<!-- Select2 CSS --> 
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> 
	<!-- Select2 JS --> 
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>

	<!--[if lt IE 9]>
		<script src="assets1/js/html5shiv.min.js"></script>
		<script src="assets1/js/respond.min.js"></script>
		<![endif]-->
</head>
<body>
<div class="inner-wrapper">
<div id="loader-wrapper">
 <div class="main">


    <div class="s1">
      <div class="s b sb1"></div>
      <div class="s b sb2"></div>
      <div class="s b sb3"></div>
      <div class="s b sb4"></div>
    </div>


    <div class="s2">
      <div class="s b sb5"></div>
      <div class="s b sb6"></div>
      <div class="s b sb7"></div>
      <div class="s b sb8"></div>
    </div>

    <div class="bigcon">
      <div class="big b"></div>
    </div>
    <div class="fav_icon_ldr">
      <img src="assets/img/bluethink_icon.ico">
    </div>


  </div>
</div>
	@include('layouts.header')
	@include('layouts.sidebar')
 
  		@yield('content')
 
  	@include('layouts.footer')
</body>
</html>	