<!DOCTYPE html>
<html lang="en">


<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login Page</title>

	<link rel="icon" type="image/x-icon" href="{{ asset('img/favicon.png') }}">

	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

	<link rel="stylesheet" href="{{ asset('assets/css/lnr-icon.css') }}">

	<link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}">

	<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">

	<!--[if lt IE 9]>
		<script src="assets/js/html5shiv.min.js"></script>
		<script src="assets/js/respond.min.js"></script>
		<![endif]-->
</head>

<body>

	<div class="inner-wrapper login-body">
		<div class="login-wrapper">
			<div class="container-fluid">
				<div class="loginbox">
					<!-- <div class="login-left">
						<img class="img-fluid" src="{{ asset('assets/img/logo.png') }}" alt="Logo">
					</div> -->
					<div class="login-right">
						<div class="login-right-wrap">
							<div class="hrms_logo">
								<img class="img-fluid" src="{{ asset('assets/img/hram_logo_new_1.png') }}" alt="Logo">
							</div>
							
							
                            @if (Session::has('error'))
                            <p class="text-danger">{{ Session::get('error') }}</p>
                            @endif
                            @if (Session::has('success'))
                                <p class="text-success">{{ Session::get('success') }}</p>
                            @endif

							<form action="{{ route('login') }}" method="post">
                            @csrf
                            
								<div class="form-group">
									<input class="form-control" name="employee_code" type="text" placeholder="Employee Code">
								</div>
                                @if ($errors->has('email'))
                                    <p class="text-danger">{{ $errors->first('employee_code') }}</p>
                                @endif
								<div class="form-group">
									<input class="form-control" name="password" type="password" placeholder="Password">
								</div>
                                @if ($errors->has('password'))
                                    <p class="text-danger">{{ $errors->first('password') }}</p>
                                @endif
								<div class="form-group">
									<select class="form-control" name="location" id="location">
										<option value="" selected>Please Select</option>
										<option value="WFO">Work From Office</option>
										<option value="WFH">Work From Home</option>
									</select>
								</div>
								@if ($errors->has('location'))
                                    <p class="text-danger">{{ $errors->first('location') }}</p>
                                @endif
								<div class="form-group lgbtn">
									<button class="login_subbtn"
										type="submit">Login</button>
								</div>
							</form>
							<div class="text-center forgotpass"><a href="{{route('forget_password')}}">Forgot Password?</a>
							</div>
						</div>
					</div>
				</div>
				<div class="login_top_content">
								<p>Let's be Together Innovative. </p>
							</div>
			</div>
			
		</div>
	</div>


	<script src="{{ asset('assets/js/jquery-3.2.1.min.js') }}"></script>

	<script src="{{ asset('assets/js/popper.min.js') }}"></script>
	<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>

	<script src="{{ asset('assets/plugins/theia-sticky-sidebar/ResizeSensor.js') }}"></script>
	<script src="{{ asset('assets/plugins/theia-sticky-sidebar/theia-sticky-sidebar.js') }}"></script>

	<script src="{{ asset('assets/js/script.js') }}"></script>


</body>


</html>