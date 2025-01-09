<!DOCTYPE html>
<html lang="en">

<!-- Mirrored from dleohr.dreamguystech.com/template-1/dleohr-horizontal/register.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 28 Aug 2023 13:43:08 GMT -->

<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Register Page</title>

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
				<div class="loginbox registerbox">
					<div class="login-right">
						<div class="login-right-wrap register_sec">
							<div class="register_logo">
								<img class="img-fluid" src="{{ asset('assets/img/logo.png') }}" alt="Logo">
							</div>
							 @if (Session::has('error'))
                            <p class="text-danger">{{ Session::get('error') }}</p>
                            @endif
                            @if (Session::has('success'))
                                <p class="text-success">{{ Session::get('success') }}</p>
                            @endif
							<form action="{{ route('register') }}" method="post">
                            @csrf
								<div class="form-group">
									<input class="form-control" name="name" type="text" placeholder="Name">
								</div>
                                @if ($errors->has('name'))
                                    <p class="text-danger">{{ $errors->first('name') }}</p>
                                @endif
								<div class="form-group">
									<input class="form-control" type="text" name="email" placeholder="Email">
								</div>
                                @if ($errors->has('email'))
                                    <p class="text-danger">{{ $errors->first('email') }}</p>
                                @endif
								<div class="form-group">
									<input class="form-control" type="text" name="employee_code" placeholder="Employee Code">
								</div>
                                @if ($errors->has('email'))
                                    <p class="text-danger">{{ $errors->first('employee_code') }}</p>
                                @endif
								<div class="form-group">
									<input class="form-control" type="text" name="role" placeholder="Employee Role">
								</div>
                                @if ($errors->has('email'))
                                    <p class="text-danger">{{ $errors->first('role') }}</p>
                                @endif
								<div class="form-group">
									<input class="form-control" type="password" name="password" placeholder="Password">
								</div>
                                @if ($errors->has('password'))
                                    <p class="text-danger">{{ $errors->first('password') }}</p>
                                @endif
								<div class="form-group">
									<input class="form-control" type="password" name="confirmed_password" placeholder="Confirm Password">
								</div>
                                
								<div class="form-group mb-0">
									<button class="registerbtn"
										type="submit">Register</button>
								</div>
							</form>

							

							

							<div class="text-center dont-have">Already have an account? <a href="login.html">Login</a>
							</div>
						</div>
					</div>
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

<!-- Mirrored from dleohr.dreamguystech.com/template-1/dleohr-horizontal/register.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 28 Aug 2023 13:43:08 GMT -->

</html>