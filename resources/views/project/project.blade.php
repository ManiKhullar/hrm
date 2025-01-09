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

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add Project</h4>
                </div>
                <div class="card-body">
                    @if (Session::has('error'))
                    <p class="text-danger">{{ Session::get('error') }}</p>
                    @endif
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                    @endif
                    <form action="{{ route('projectsave') }}" method="POST">
                        @csrf	
                        <div class="row">
                            <div class="col-sm-6 leave-col">
                                <div class="form-group add_project_form">
                                    <label class="project_label_c">Project Name</label>
                                    <input type="text" required class="form-control" name="project_name">
                                </div>
                                @if ($errors->has('project_name'))
                                <p class="text-danger">{{ $errors->first('project_name') }}</p>
                                @endif	
                            </div>
                            <div class="col-sm-6 leave-col">
                                <div class="form-group add_project_form">
                                    <label class="project_label_c">Vendor Name</label>
                                    <input type="text" required class="form-control" name="vendor_name">
                                </div>
                                @if ($errors->has('vendor_name'))
                                <p class="text-danger">{{ $errors->first('vendor_name') }}</p>
                                @endif 
                            </div>	
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group add_project_form">
                                    <label class="project_label_c">Project Strat Date</label>
                                    <input type="text" required class="form-control datetimepicker" name="project_startdate">
                                </div>
                                @if ($errors->has('project_startdate'))
                                <p class="text-danger">{{ $errors->first('project_startdate') }}</p>
                                @endif
                            </div>
                            <div class="col-sm-6 leave-col">
                                <div class="form-group add_project_form">
                                    <label class="project_label_c">Project End Date</label>
                                    <input type="text" class="form-control datetimepicker" name="project_enddate">
                                </div>
                                @if ($errors->has('project_enddate'))
                                <p class="text-danger">{{ $errors->first('project_enddate') }}</p>
                                @endif 
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group add_project_form">
                                    <label class="project_label_c">
                                        Status
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select required class="form-control select" name="status">
                                        <option value="1">Active</option>
                                        <option value="0">Deactivate</option>
                                    </select>
                                </div>
                                @if ($errors->has('status'))
                                <p class="text-danger">{{ $errors->first('status') }}</p>
                                @endif 
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group add_project_form">
                                    <label class="project_label_c">
                                        Project Type
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select required class="form-control select" name="is_billable">
                                    
                                        <option value="1">Billable</option>
                                        <option value="0">Non-Billable</option>
                                    </select>
                                </div>
                                @if ($errors->has('status'))
                                <p class="text-danger">{{ $errors->first('status') }}</p>
                                @endif 
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Submit</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-12">
                    <div class="card ctm-border-radius shadow-sm grow">
                        <div class="card-header">
                            <div class="search_emp">
                                <h4 class="card-title mb-0">Project List</h4>
                                <form autocomplete="off">
                                    <div class="empser_sec">
                                        <input type="text" class="form-control" name="search" id="search_data"  placeholder="search">
                                        <input type="hidden" value="{{url('/')}}" id="url" name="url">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive" id="project_list_data">
                                    <table class="table custom-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Project Name</th>
                                                <th>Vendor Name</th>
                                                <th>Project Type</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Status</th>
                                                <th class='text-center'>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="ajaxProjectData">
                                            @foreach ($Projects as $project)
                                            <tr>
                                                <td>{{ ++$i }}</td>
                                                <td>{{ $project->project_name }}</td>
                                                <td>{{ $project->vendor_name }}</td>
                                                <td>
                                                    @if($project->is_billable == 1)
                                                        Billable
                                                    @else
                                                        Non-Billable
                                                    @endif
                                                </td>
                                                <td>{{ $project->project_startdate }}</td>
                                                <td>{{ $project->project_enddate }}</td>
                                                <td>
                                                    @if($project->status == 1)
                                                        Active
                                                    @else
                                                        Deactivate
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($project->status == 0)
                                                        <a onclick="return confirm('Are you want to Activate?')" href="{{ url('projectupdate/'.$project->id.'/1') }}">Active</a>
                                                    @else
                                                        <a onclick="return confirm('Are you want to Deactivate?')" href="{{ url('projectupdate/'.$project->id.'/0') }}">Deactivate</a>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {!! $Projects->links() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .justify-between {
        width: 75px;
    }
</style>
<script>
	$(document).ready(function() {
		$("#search_data").keyup(function(e) {
			var currentInput = $(this).val();
			$.ajax({
				url: "{{ route('projectfilter') }}",
				type: "POST",
				data: {"_token": "{{ csrf_token() }}",
							'search':currentInput},
				dataType: "json",
				success: function (data) {
					
					var html = "<table class='table custom-table mb-0 table-hover'><thead><tr><th>No</th><th>Project Name</th><th>Vendor Name</th><th>Start Date</th><th>End Date</th><th>Status</th><th class='text-center'>Action</th></tr></thead><tbody id='ajaxProjectData'>";

                    var i = 0;
					$.each(data, function(index, value) {
                        i = ++i;
                        if(value.project_enddate === null){
                            value.project_enddate = '';
                        }
						if(value.status == 1){
							var dataStatus = 'Deactivate';
							var Status = 'Active';
							var prostatuschange = $("#url").val()+'/projectupdate/'+value.id+'/0';
						} else{
							var dataStatus = 'Active';
							var Status = 'Deactivate';
							var prostatuschange = $("#url").val()+'/projectupdate/'+value.id+'/1';
						}
						html+="<tr><td>"+i+"</td><td>"+value.project_name+"</td><td>"+value.vendor_name+"</td><td>"+value.project_startdate+"</td><td>"+value.project_enddate+"</td><td>"+Status+"</td><td><a href="+prostatuschange+">"+dataStatus+"</a></td></tr>";
					});
					html+="</tbody></table>";
					$('#project_list_data').html(html);
				}
			});
		});
	})
</script>

@endsection