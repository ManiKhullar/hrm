@extends('layouts.layout')

<style>
	.search_emp {
		display: flex;
		align-items: center;
		justify-content: space-between;
		gap: 10px;
	}
	.empser_sec {
		display: flex;
		align-items: center;
		gap: 10px;
	}
	.empser_sec button {
		margin-top: 0px !important;
	}
	.search_emp a {
		margin-top: -16px !important;
	}
	.empser_sec input {
		height: 40px;
	}
</style>

@section('content')

<div class="col-xl-9 col-lg-8  col-md-12">
	<div class="accordion add-employee" id="accordion-details">
		<div class="card shadow-sm ctm-border-radius">
			<div class="card-header" id="basic1">
				<div class="search_emp">
					<a href="{{ url('employee_add') }}"  class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Add Employee</a>
					<form autocomplete="off">
						<div class="empser_sec">
							<input type="text" id="search" class="form-control" name="search" id="search_data"  placeholder="search">
						</div>
					</form>
				</div>
			</div>
			<div class="card-body p-0">
				<div class="col-md-12">
					<div class="card ctm-border-radius shadow-sm">
						<div class="card-header">
							<h4 class="card-title mb-0">Employee List</h4>
						</div>
						<div class="card-body">
							<div class="employee-office-table">
								<div class="table-responsive" id="employee_list_data">
									<table class="table custom-table mb-0 table-hover">
										<thead>
											<tr>
												<th>Employee Name</th>
												<th>Employee Code</th>
												<th>Email</th>
												
												<th>Joining Date</th>
												<th>Policy</th>
												<th>Status</th>
												<th class='text-center'>Action</th>
											</tr>
										</thead>
										<tbody id="ajaxData">
										@foreach($employeeData as $employeeDatas)
											<tr>
												<td>
													<a href="#" class="avatar">
													<?php if($employeeDatas->profile_pic!=""){
														$profileImage = $employeeDatas->profile_pic;
														$filepath = $employeeDatas->employee_code;
													?>    
														<img
														alt="avatar image"
														src="{{ asset('doc_images'.$filepath.'/'.$profileImage) }}"
														class="img-fluid" width="55">
										
														<?php }else{?>
														<img src="{{ asset('assets/img/profiles/logo.png') }}" alt="user avatar" class="rounded-circle img-fluid" width="55">
														<?php }?>
													</a>
													<h2>{{ucwords($employeeDatas->name)}}</h2>
												</td>
												<td>{{$employeeDatas->employee_code}}</td>
												<td>{{$employeeDatas->email}}</td>
												
												<td><?php echo date("d/m/Y",strtotime($employeeDatas->joining_date)); ?></td>
												<td>{{$employeeDatas->accept_policy}}</td>
												<td>
													<?php if($employeeDatas->status == 1){
														echo "Active";
                                                    }else{
                                                        echo "Inactive";
                                                    }?>
												</td>
												<td><a class="btn btn-primary" href="{{ url('employee_view/'.$employeeDatas->id) }}">View</a>
													| <a class="btn btn-primary" href="{{ url('empattendance/'.$employeeDatas->id) }}">Attendance</a>
													<a class="btn btn-primary" href="{{ url('emp_leave_add/'.$employeeDatas->id) }}">Apply Leave</a>
													|<?php if($employeeDatas->status == 0){ ?>
														<a class="btn btn-warning" onclick="return confirm('Are you want to Activate?')" href="{{ url('empstatuschange/'.$employeeDatas->id.'/1') }}">Active</a>
                                                    <?php }else{ ?>
                                                        <a class="btn btn-warning" onclick="return confirm('Are you want to Inactivate?')" href="{{ url('empstatuschange/'.$employeeDatas->id.'/0') }}">Inactive</a>
                                                    <?php } ?>
												</td>
											</tr>
											@endforeach
										</tbody>
									</table>
									{!! $employeeData->links() !!}
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>	
</div>
<input type="hidden" value="{{url('/')}}" id="url" name="url">

<script>
	$(document).ready(function() {
		$("input").keyup(function(e) {
			var currentInput = $(this).val();
			var inputLen = currentInput.length;
			
			console.log(currentInput);
			if(inputLen >=3){
			$.ajax({
				url: "{{ route('employeefilter') }}",
				type: "POST",
				data: {"_token": "{{ csrf_token() }}",
							'search':currentInput},
				dataType: "json",
				success: function (data) {
					
					var html = "<table class='table custom-table mb-0 table-hover'><thead><tr><th>Employee Name</th><th>Employee Code</th><th>Email</th><th>Gender</th><th>Joining Date</th><th>Policy</th><th>Status</th><th class='text-center'>Action</th></tr></thead><tbody id='ajaxData'>";

					$.each(data, function(index, value) {
						var image = value.profile_pic;
						var filepath = value.employee_code+"/";
						var profile_image =$("#url").val()+'/assets/img/profiles/logo.png';
						if(image!=="" || image!==null){
							var profile_image =$("#url").val()+"/doc_images"+filepath+image;
						}

						var emp_url = $("#url").val()+'/employee_view/'+value.id;
						var empattendance = $("#url").val()+'/empattendance/'+value.id;
						var leaveapply = $("#url").val()+'/emp_leave_add/'+value.id;
						console.log(moment(value.doj).format('DD/MM/yyyy'));
						if(value.status == 1){
							var dataStatus = 'Inactive';
							var Status = 'Active';
							var empstatuschange = $("#url").val()+'/empstatuschange/'+value.id+'/0';
						} else{
							var dataStatus = 'Active';
							var Status = 'Inactive';
							var empstatuschange = $("#url").val()+'/empstatuschange/'+value.id+'/1';
						}
						html+="<tr><td><a href='#' class='avatar'><img alt='avatar image' src="+profile_image+" class='img-fluid' width='55'></a><h2><a href='#'>"+value.name+"</a></h2></td><td>"+value.employee_code+"</td><td>"+value.email+"</td><td>"+value.gender+"</td><td>"+value.doj+"</td><td>"+value.accept_policy+"</td><td>"+Status+"</td><td><a class='btn btn-primary' href="+emp_url+">View</a> | <a class='btn btn-primary' href="+empattendance+">Attendance</a> |<a class='btn btn-primary' href="+leaveapply+">Apply Leave</a> | <a class='btn btn-warning' href="+empstatuschange+">"+dataStatus+"</a></td></tr>";
					});
					html+="</tbody></table>";
					$('#employee_list_data').html(html);
				}
			});
		}
		});
	})
</script>

@endsection
