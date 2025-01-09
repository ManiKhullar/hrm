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
<th?php 
$userArray  = [];
$skillArray = [];
?>
@section('content')

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Project Reports</h4>
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
                    <form action="{{ route('projectreport') }}" method="POST">
                        @csrf	
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>
                                        Project Name
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select" name="project_id" id="project_id">
                                        <option value="">Select</option>
                                        @foreach ($projects as $project)
                                        
                                        <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('project_id'))
                                <p class="text-danger">{{ $errors->first('project_id') }}</p>
                                @endif	
                            </div>	
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>
                                        Project Assign To
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select" name="user_id" id="user_id">
                                        <option value="">Select</option>
                                        @foreach ($users as $user)
                                        <?php $userArray[$user->id] = $user->name; ?>
                                        <option value="{{ $user->id }}" @if(isset($req->user_id) && $req->user_id == $user->id) selected @endif >{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('user_id'))
                                <p class="text-danger">{{ $errors->first('user_id') }}</p>
                                @endif	
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group add_project_form">
                                    <label class="project_label_c">
                                        Vendor
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select" id ="vendor" name="vendor">
                                    <option value="">Select</option>
                                        @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->vendor_name }}" @if(isset($req->vendor) && $req->vendor == $vendor->vendor_name) selected @endif >{{ $vendor->vendor_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('status'))
                                <p class="text-danger">{{ $errors->first('status') }}</p>
                                @endif 
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>
                                        Project Technology
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select" name="skill" id="skill">
                                        <option value="">Select</option>
                                        @foreach ($skills as $skill)
                                        <?php $skillArray[$skill->id] = $user->tech_name; ?>
                                        <option value="{{ $skill->id }}" @if(isset($req->skill) && $req->skill == $skill->id) selected @endif>{{ $skill->tech_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('skill'))
                                <p class="text-danger">{{ $errors->first('userskiskillllid') }}</p>
                                @endif	
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group add_project_form">
                                    <label class="project_label_c">
                                        Project Status
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select" name="status">
                                    <option value=" ">All</option>
                                        <option value="1" @if(isset($req->status) && $req->status ==1) selected @endif >Active</option>
                                        <option value="0" @if(isset($req->status) && $req->status ==0) selected @endif >Deactivate</option>
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
                                    <select class="form-control select" name="is_billable">
                                    <option value=" ">All</option>
                                        <option value="1" @if(isset($req->is_billable) && $req->is_billable ==1) selected @endif>Billable</option>
                                        <option value="0" @if(isset($req->is_billable) && $req->is_billable ==0) selected @endif>Non-Billable</option>
                                    </select>
                                </div>
                                @if ($errors->has('status'))
                                <p class="text-danger">{{ $errors->first('status') }}</p>
                                @endif 
                            </div>
</div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Submit</button>
                           <!-- <input type="reset" name="Reset" class="btn btn-theme button-1 text-white ctm-border-radius mt-4" /> -->
                        </div>
                    </form>
                </div>
                <div class="col-md-12">
                    <div class="card ctm-border-radius shadow-sm grow">
                        <div class="card-header">
                            <div class="search_emp">
                                <h4 class="card-title mb-0">Project List</h4>
                                
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
                                                <th>Project Type</th>
                                                <th>Vendor Name</th>
                                                <!--<th>Manager & Developer Name</th> -->
                                                <th>Start Date</th>
                                                @if(isset($req->user_id))
                                                <th>Manager Name</th>
                                                <th>Developer Name</th>
                                                @else
                                                <th>Manager Name / Developer Name</th>
                                                @endif 
                                                
                                                <th>Status</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody id="ajaxProjectData">
                                            
                                        @foreach ($projectList as $key=>$project)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ $project->project_name }}</td>
                                                <td>@if($project->is_billable == 1)
                                                        Billable
                                                    @else
                                                        Non-Billable
                                                    @endif</td>
                                                <td>{{ $project->vendor_name }}</td>
                                                <td>{{ $project->project_startdate }}</td>
                                                
                                                @if(isset($req->user_id))
                                                <td>{{$userArray[$project->mid]}}</td>
                                                <td>{{$userArray[$project->devid]}}</td>
                                                @else
                                                <td>{{  App\Http\Helper\CommonHelper::getTeamList($project->id) }}</td>
                                                @endif
                                                <td>
                                                    @if($project->status == 1)
                                                        Active
                                                    @else
                                                        Deactive
                                                    @endif
                                                </td>
</tr>
@endforeach
                                        </tbody>
                                    </table>
                                   
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

        $("#user_id").select2();
 $("#project_id").select2();
 $("#skill").select2();
 $("#vendor").select2();
		
	})
</script>

@endsection