@extends('layouts.layout')
 
@section('content')

@if (Session::has('error'))
	<p class="text-danger">{{ Session::get('error') }}</p>
@endif
@if ($message = Session::get('success'))
	<div class="alert alert-success">
		<p>{{ $message }}</p>
	</div>
@endif

<form class="col-xl-9 col-lg-8  col-md-12" action="{{ route('employee_save') }}" method="post" enctype="multipart/form-data">
	<div class="accordion add-employee" id="accordion-details">
		<div class="card shadow-sm ctm-border-radius">
			<div class="card-header" id="basic1">
				<h4 class="cursor-pointer mb-0">
					<a class=" coll-arrow d-block text-dark" href="javascript:void(0)"
						data-toggle="collapse" data-target="#basic-one" aria-expanded="true">
						Basic Details
					</a>
				</h4>
			</div>
			
			<div class="card-body p-0">
				<div id="basic-one" class="collapse show ctm-padding" aria-labelledby="basic1"
					data-parent="#accordion-details">
					
					@csrf
						<div class="row">
							<div class="col-md-6 col-12 form-group">
								<input type="text" class="form-control" name="first_name" placeholder="First Name">
							</div>
							@if ($errors->has('first_name'))
								<p class="text-danger">{{ $errors->first('first_name') }}</p>
							@endif
							<div class="col-md-6 col-12 form-group">
								<input type="text" class="form-control" name="last_name" placeholder="Last Name">
							</div>
							@if ($errors->has('last_name'))
								<p class="text-danger">{{ $errors->first('last_name') }}</p>
							@endif
							<div class="col-md-6 col-12 form-group">
								<p>Email<span class="text-danger">*</span></p>
								<input type="email" class="form-control" name="email" placeholder="Email">
							</div>
							@if ($errors->has('email'))
								<p class="text-danger">{{ $errors->first('email') }}</p>
							@endif
							<div class="col-md-6 col-12 form-group">
								<p>Date of birth <span class="text-danger">*</span></p>
								<input type="date" class="form-control" name="dob" placeholder="Date of Birth">
							</div>
							@if ($errors->has('dob'))
								<p class="text-danger">{{ $errors->first('dob') }}</p>
							@endif
							<div class="col-md-6 col-12 form-group">
								<p class="mb-2">Gender<span class="text-danger">*</span></p>
								<div class="custom-control-inline">
									<input type="radio" name="gender" value="male" class="" checked>
									<label class=""
										for="Male">Male</label>
								</div>
								<div class="custom-control-inline">
									<input type="radio" name="gender" value="female" class="">
									<label class="" for="Female">Female</label>
								</div>
							</div>
							<div class="col-md-6 col-12 form-group">
								<p class="mb-2">Blood Group</p>
								<div class="custom-control-inline">
									<input type="radio"  
										name="blood_group" value="A+" checked>
									<label 
										for="A">A+</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" value="B+" 
										name="blood_group">
									<label 
										for="B">B+</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" value="AB+" 
										name="blood_group">
									<label 
										for="AB">AB+</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" value="AB-" 
										name="blood_group">
									<label 
										for="AB">AB-</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" value="B-" 
										name="blood_group">
									<label 
										for="B+">B-</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" value="A-" 
										name="blood_group">
									<label 
										for="A+">A-</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" value="O-" 
										name="blood_group">
									<label 
										for="O">O-</label>
								</div>
								<div class="custom-control custom-radio custom-control-inline">
									<input type="radio" value="O+" 
										name="blood_group">
									<label 
										for="O">O+</label>
								</div>
							</div>
							<div class="col-md-6 col-12 form-group">
								<p>Date Of Joining <span class="text-danger">*</span></p>
								<input type="date" class="form-control" name="doj" placeholder="Date of Joining">
								@if ($errors->has('doj'))
								<p class="text-danger">{{ $errors->first('doj') }}</p>
								@endif
							</div>
							<div class="col-md-3 col-12 form-group">
								<label>Role<span class="text-danger">*</span></label>
								<select class="form-control select" name="department_role">
									<option value="">Please Select Role</option>
									@foreach ($departments as $department)
									<option value="{{ $department->id }}">{{ $department->department_name }}</option>
									@endforeach
								</select>
								@if ($errors->has('department_role'))
								<p class="text-danger">{{ $errors->first('department_role') }}</p>
								@endif
							</div>
							<div class="col-md-3 col-12 form-group">
								<label>Shift<span class="text-danger">*</span></label>
								<select class="form-control select" name="emp_shift">
									<option value="">Please Select Shift</option>
									@foreach ($empShift as $shift)
									<option value="{{ $shift->id }}">{{ $shift->shift_name }}</option>
									@endforeach
								</select>
								@if ($errors->has('emp_shift'))
								<p class="text-danger">{{ $errors->first('emp_shift') }}</p>
								@endif
							</div>
							
							<div class="col-6 form-group">
							<label>Designation<span class="text-danger">*</span></label>
								<input type="text" class="form-control" name="job_title" placeholder="Designation">
								@if ($errors->has('job_title'))
								<p class="text-danger">{{ $errors->first('job_title') }}</p>
								@endif
							</div>
							<div class="col-6 form-group">
								<label>Technology<span class="text-danger">*</span></label>
								<select class="form-control select" name="technology">
									<option value="">Please Select Technology</option>
									@foreach ($technology as $data)
										<option value="{{ $data->id }}">{{ $data->tech_name }}</option>
									@endforeach
								</select>
								@if ($errors->has('technology'))
									<p class="text-danger">{{ $errors->first('technology') }}</p>
								@endif
							</div>
							<div class="col-md-6 col-12 form-group mb-0">
								<p class="mb-2">Employment Type </p>
								<div class="custom-control-inline">
									<input type="radio"  id="Permanent" name="employment_type" value="fte" checked>
									<label for="FTE">FTE</label>
								</div>
								<div class="custom-control-inline">
									<input type="radio"  id="Freelancer" name="employment_type" value="contractor">
									<label for="Contractor">Contractor</label>
								</div>
								
							</div>
							<div class="col-md-6 col-12 form-group">
									<p>Employee Band <span class="text-danger">*</span></p>
									<select class="form-control select" name="employee_band">
										<option value="">Please Select Employee Band</option>
										<option value="E1">E1</option>
										<option value="E2">E2</option>
										<option value="E3">E3</option>
										<option value="E4">E4</option>
										<option value="E5">E5</option>
									</select>
								@if ($errors->has('employee_band'))
								<p class="text-danger">{{ $errors->first('employee_band') }}</p>
								@endif
							</div>
							<div class="col-md-6 col-12 form-group">
									<p>Employee Type <span class="text-danger">*</span></p>
									<select class="form-control select" required name="is_paid">
										<option value="">Please Select Type</option>
										<option value="1">Paid</option>
										<option value="0">Un-Paid</option>
										
									</select>
								@if ($errors->has('is_paid'))
								<p class="text-danger">{{ $errors->first('is_paid') }}</p>
								@endif
							</div>
						</div>
					
				</div>
			</div>
		</div>
		<div class="card shadow-sm ctm-border-radius">
			<div class="card-header" id="headingThree">
				<h4 class="cursor-pointer mb-0">
					<a class="coll-arrow d-block text-dark" href="javascript:void(0)"
						data-toggle="collapse" data-target="#term-office">
						Account Details
					</a>
				</h4>
			</div>
			<div class="card-body p-0">
				<div id="term-office" class="collapse show ctm-padding"
					aria-labelledby="headingThree" data-parent="#accordion-details">
					<div class="row">
						<div class="col-md-6 col-12 form-group">
						<label>Bank Name</label>
							<input type="text" class="form-control" name="bank_name" placeholder="Bank Name">
						</div>
						<div class="col-md-6 col-12 form-group">
						<label>Account Number</label>
							<input type="text" class="form-control" name="acc_no" placeholder="Account Number">
						</div>
						<div class="col-md-6 col-12 form-group">
						<label>IFSC Code</label>
							<input type="text" class="form-control" name="ifsc" placeholder="IFSC">
						</div>
						<div class="col-md-6 col-12 form-group">
						<label>Monthly Salary</label>
							<input type="text" class="form-control" name="salary" placeholder="Monthly Salary">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card shadow-sm ctm-border-radius">
			<div class="card-header" id="headingTwo">
				<h4 class="cursor-pointer mb-0">
					<a class="coll-arrow d-block text-dark" href="javascript:void(0)"
						data-toggle="collapse" data-target="#employee-det">
						Current Address
					</a>
				</h4>
			</div>
			<div class="card-body p-0">
				<div id="employee-det" class="collapse show ctm-padding"
					aria-labelledby="headingTwo" data-parent="#accordion-details">
					
						<div class="row">
							<div class="col-md-6 col-12 form-group">
								<input type="text" class="form-control" name="street" placeholder="Street Address">
							</div>
							
							<div class="col-md-6 form-group">
								<select class="form-control select" name="state" id="statelist">
									<option value="" selected>Select State</option>
									@foreach($state as $statedate)
									<option value="{{$statedate->state}}">{{$statedate->state}}</option>
									@endforeach
								</select>
							</div>
							<div class="col-md-6 col-12 form-group">
								<select class="form-control select" name="city" id="citylist">
									<option value="" selected>Select City</option>
								</select>
							</div>
							<div class="col-md-6 col-12 form-group">
								<input type="text" class="form-control" name="pincode" placeholder="Pincode">
							</div>
							<div class="col-md-6 col-12 form-group">
								<select class="form-control select" name="country">
									<option value="">Select Country</option>
									<option value="India">India</option>
								</select>
							</div>
						</div>
				</div>
			</div>
		</div>

		<div class="card shadow-sm ctm-border-radius">
			<div class="card-header" id="headingTwo">
				<h4 class="cursor-pointer mb-0">
					<a class="coll-arrow d-block text-dark" href="javascript:void(0)"
						data-toggle="collapse" data-target="#employee-det">
						Permanent Address
					</a>
				</h4>
			</div>
			<div class="card-body p-0">
				<div id="employee-det" class="collapse show ctm-padding"
				aria-labelledby="headingTwo" data-parent="#accordion-details">
					<div class="row">
						<div class="col-md-6 col-12 form-group">
							<input type="text" class="form-control" name="p_street" placeholder="Street Address">
						</div>
						<div class="col-md-6 form-group">
							<select class="form-control select" name="p_state" id="p_statelist">
								<option value="" selected>Select State</option>
								@foreach($state as $statedate)
								<option value="{{$statedate->state}}">{{$statedate->state}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-md-6 col-12 form-group">
							<select class="form-control select" name="p_city" id="p_citylist">
								<option value="" selected>Select City</option>
							</select>
						</div>
						<div class="col-md-6 col-12 form-group">
							<input type="text" class="form-control" name="p_pincode" placeholder="Pincode">
						</div>
						<div class="col-md-6 col-12 form-group">
							<select class="form-control select" name="country">
								<option value="">Select Country</option>
								<option value="India">India</option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card shadow-sm ctm-border-radius">
			<div class="card-header" id="headingThree">
				<h4 class="cursor-pointer mb-0">
					<a class="coll-arrow d-block text-dark" href="javascript:void(0)"
						data-toggle="collapse" data-target="#term-office">
						Family Details
					</a>
				</h4>
			</div>
			<div class="card-body p-0">
				<div id="term-office" class="collapse show ctm-padding"
					aria-labelledby="headingThree" data-parent="#accordion-details">
					<div class="row">
						<div class="col-md-6 col-12 form-group">
							<input type="text" class="form-control" name="father_name" placeholder="Father Name">
						</div>
						<div class="col-md-6 col-12 form-group">
							<input type="text" class="form-control" name="mother_name" placeholder="Mother Name">
						</div>
						<div class="col-md-6 col-12 form-group">
							<input type="text" class="form-control" name="spouse_name" placeholder="Spouse Name">
						</div>
						<div class="col-md-6 form-group">
							<select class="form-control select" name="number_type">
								<option value="1">Mother</option>
								<option value="2">Father</option>
								<option value="3">Spouse</option>
							</select>
						</div>
						<div class="col-md-6 col-12 form-group">
							<input type="text" class="form-control" name="contact_number" placeholder="Phone Number">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card shadow-sm ctm-border-radius">
			<div class="card-header" id="headingFour">
				<h4 class="cursor-pointer mb-0">
					<a class="coll-arrow d-block text-dark" href="javascript:void(0)"
						data-toggle="collapse" data-target="#salary_det">
						Education Details
					</a>
				</h4>
			</div>
			<div class="card-body p-0">
				<div id="salary_det" class="collapse show ctm-padding" aria-labelledby="headingFour"
					data-parent="#accordion-details">
					<div class="row">
						<div class="col-md-6 col-12 form-group">
							<p>High School Marksheet</p>
							<input type="file" class="form-control" name="high_marksheet">
						</div>
						<div class="col-md-6 col-12 form-group">
							<p>Intermediate School Marksheet</p>
							<input type="file" class="form-control" name="inter_marksheet">
						</div>
						<div class="col-md-6 col-12 form-group">
							<p>Graduation Marksheet</p>
							<input type="file" class="form-control" name="graducation_marksheet">
						</div>
						<div class="col-md-6 col-12 form-group">
							<p>Post Graduation Marksheet</p>
							<input type="file" class="form-control" name="post_graduation_marksheet">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card shadow-sm ctm-border-radius">
			<div class="card-header" id="headingFour">
				<h4 class="cursor-pointer mb-0">
					<a class="coll-arrow d-block text-dark" href="javascript:void(0)"
						data-toggle="collapse" data-target="#salary_det">
						Document Section
					</a>
				</h4>
			</div>
			<div class="card-body p-0">
				<div id="salary_det" class="collapse show ctm-padding" aria-labelledby="headingFour"
					data-parent="#accordion-details">
					<div class="row">
						<div class="col-md-6 col-12 form-group">
							<p>Profile Photo</p>
							<input type="file" class="form-control" name="profile_pic">
						</div>
						<div class="col-md-6 col-12 form-group">
							<p>Addhar Number</p>
							<input type="text" class="form-control" name="addhar_number" placeholder="Addhar Number">
						</div>
						<div class="col-md-6 col-12 form-group">
							<p>Addhar Doc File</p>
							<input type="file" class="form-control" name="addhar_doc_file">
						</div>
						<div class="col-md-6 col-12 form-group">
							<p>PAN Number</p>
							<input type="text" class="form-control" name="pan_number" placeholder="PAN Number">
						</div>
						<div class="col-md-6 col-12 form-group">
							<p>PAN Doc File</p>
							<input type="file" class="form-control" name="pan_doc_file">
						</div>
						<div class="col-md-6 col-12 form-group">
							<p>Offer Letter</p>
							<input type="file" class="form-control" name="offer_letter">
						</div>
						<div class="col-md-6 col-12 form-group">
							<p>Reliving Letter</p>
							<input type="file" class="form-control" name="relieving_latter">
						</div>
						<div class="col-md-6 col-12 form-group">
							<p>Regination Letter</p>
							<input type="file" class="form-control" name="resignation_letter">
						</div>
						<div class="col-md-6 col-12 form-group">
							<p>Appointment Letter</p>
							<input type="file" class="form-control" name="appointment_latter">
						</div>
						<div class="col-md-6 col-12 form-group">
							<p>Bank Statment</p>
							<input type="file" class="form-control" name="bank_statment">
						</div>
						<div class="col-md-6 col-12 form-group">
							<p>Salary Slip1</p>
							<input type="file" class="form-control" name="salary_slip1">
						</div>
						<div class="col-md-6 col-12 form-group">
							<p>Salary Slip2</p>
							<input type="file" class="form-control" name="salary_slip2">
						</div>
						<div class="col-md-6 col-12 form-group">
							<p>Salary Slip3</p>
							<input type="file" class="form-control" name="salary_slip3">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card shadow-sm ctm-border-radius">
			<div class="card-header" id="headingFour">
				<h4 class="cursor-pointer mb-0">
					<a class="coll-arrow d-block text-dark" href="javascript:void(0)"
						data-toggle="collapse" data-target="#salary_det">
						Communication Details
					</a>
				</h4>
			</div>
			<div class="card-body p-0">
				<div id="salary_det" class="collapse show ctm-padding" aria-labelledby="headingFour"
					data-parent="#accordion-details">
					<div class="row">
						<div class="col-md-6 col-12 form-group">
							<input type="text" class="form-control" name="mobile_number" placeholder="Mobile Number">
						</div>
						<div class="col-md-6 col-12 form-group">
							<input type="text" class="form-control" name="company_email_id" placeholder="Company Email Id">
						</div>
						<div class="col-md-6 col-12 form-group">
							<input type="text" class="form-control" name="client_email_id" placeholder="Client Email Id">
						</div>
						<div class="col-md-6 col-12 form-group">
							<input type="text" class="form-control" name="email_id" placeholder="Email Id">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="card shadow-sm ctm-border-radius">
			<div class="card-header" id="headingFour">
				<h4 class="cursor-pointer mb-0">
					<a class="coll-arrow d-block text-dark" href="javascript:void(0)"
						data-toggle="collapse" data-target="#salary_det">
						Previous Empoyement Details
					</a>
				</h4>
			</div>
			<div class="card-body p-0  previous-employment">
				<div class="col-md-2">
					<div class="form-group change">
						<label for="">&nbsp;</label><br/>
						<a class="btn btn-success add-more">+ Add More</a>
					</div>
				</div>
				<div id="salary_det" class="collapse show ctm-padding" aria-labelledby="headingFour"
					data-parent="#accordion-details">
					<div class="row">
						<div class="col-md-6 col-12 form-group">
							<input type="text" class="form-control" name="company_name[]" placeholder="Company Name">
						</div>
						<div class="col-md-6 col-12 form-group">
							<input type="text" class="form-control" name="role[]" placeholder="Company Role">
						</div>
						<div class="col-md-6 col-12 form-group">
								<p>Start Date</p>
								<input type="date" class="form-control" name="start_date[]" placeholder="Start date">
						</div>
						<div class="col-md-6 col-12 form-group">
								<p>End Date</p>
								<input type="date" class="form-control" name="end_date[]" placeholder="End Date">
						</div>
						<div class="col-md-6 col-12 form-group">
							<input type="text" class="form-control" name="company_emp_ref_name[]" placeholder="Company Reference Name">
						</div>
						<div class="col-md-6 col-12 form-group">
							<input type="text" class="form-control" name="company_emp_ref_email[]" placeholder="Company Reference Email">
						</div>
						<div class="col-md-6 col-12 form-group">
							<input type="text" class="form-control" name="company_emp_ref_mobile[]" placeholder="Company Reference Mobile">
						</div>
						<div class="col-md-6 col-12 form-group">
							<input type="text" class="form-control" name="company_emp_ref_role[]" placeholder="Company Reference Role">
						</div>
					</div>
				</div>
				
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-12">
			<div class="submit-section text-center btn-add">
				<button class="btn btn-theme text-white ctm-border-radius button-1 employee_add" id="employee_add">Add Employee</button>
			</div>
		</div>
	</div>
</form>

<script>
	$(document).ready(function() {
		$("body").on("click",".add-more",function(e){ 
			e.preventDefault();
			var html = $(".previous-employment").first().clone();
			$(html).find(".change").html("<label for=''>&nbsp;</label><br/><a class='btn btn-danger remove'>- Remove</a>");
			$(".previous-employment").last().after(html);
		});

		$("body").on("click",".remove",function(e){ 
			e.preventDefault();
			$(this).parents(".previous-employment").remove();
		});
	});
</script>
<script type="text/javascript"> 
	$(document).ready(function () {
		$('#statelist').on('change', function() {
			var userURL = "{{  url('city_ajax') }}";

			$.ajax({
				url: userURL,
				type: 'POST',
				data: {
					"_token": "{{ csrf_token() }}",
					'state':this.value},
				dataType: 'json',
				success: function(data) {
				let htmldata="";
					data.map(datas=>{
						htmldata+= "<option value="+datas.city+">"+datas.city+"</option>";
					})
					$('#citylist').html(htmldata);
				}
			});
		});  
		
		
			// $("#employee_add").on("click", function(e) {
			// 	e.preventDefault(); // cancel default action
			// 	let userURL = "{{  url('employee_save') }}";
			// 	alert('aaaaaaaaaaaa');
			// 	//let formdata = new FormData();
			// 	let formToWorkOn = document.querySelector('#my-awesome-dropzone');
			//     let formData = new FormData(formToWorkOn);
			// 	//console.log(formData);
			// 	//return false;
			// 	//var form_data = new FormData($("my-awesome-dropzone"));
			// 	//
			// 		$.ajax({
			// 		url: userURL,
			// 		type: 'POST',
			// 		data:$('#my-awesome-dropzone').serialize(),
			// 		success: function(data) {
					
			// 		}
			// 	});
			// 	return false;
			// });
	
	});
</script>
<script type="text/javascript"> 
	$(document).ready(function () {
		$('#p_statelist').on('change', function() {
			var userURL = "{{  url('city_ajax') }}";

			$.ajax({
				url: userURL,
				type: 'POST',
				data: {
					"_token": "{{ csrf_token() }}",
					'state':this.value},
				dataType: 'json',
				success: function(data) {
				let htmldata="";
					data.map(datas=>{
						htmldata+= "<option value="+datas.city+">"+datas.city+"</option>";
					})
					$('#p_citylist').html(htmldata);
				}
			});
		});  
		
		
			// $("#employee_add").on("click", function(e) {
			// 	e.preventDefault(); // cancel default action
			// 	let userURL = "{{  url('employee_save') }}";
			// 	alert('aaaaaaaaaaaa');
			// 	//let formdata = new FormData();
			// 	let formToWorkOn = document.querySelector('#my-awesome-dropzone');
			//     let formData = new FormData(formToWorkOn);
			// 	//console.log(formData);
			// 	//return false;
			// 	//var form_data = new FormData($("my-awesome-dropzone"));
			// 	//
			// 		$.ajax({
			// 		url: userURL,
			// 		type: 'POST',
			// 		data:$('#my-awesome-dropzone').serialize(),
			// 		success: function(data) {
					
			// 		}
			// 	});
			// 	return false;
			// });
	
	});
</script>

@endsection