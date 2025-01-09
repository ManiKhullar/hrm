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
                    <h4 class="card-title mb-0">Employee Reports</h4>
                </div>
                <div class="card-body">
                    
                    <form action="{{ route('empreport') }}" method="POST">
                        @csrf	
                        
                        <div class="row">
                           
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>
                                        Project Technology
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select" name="skill" id="skill">
                                        <option value="">Select</option>
                                        @foreach ($skills as $skill)
                                        
                                        <option value="{{ $skill->id }}" @if(isset($req->skill) && $req->skill == $skill->id) selected @endif>{{ $skill->tech_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                	
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group" style="margin-top:10px !important;">
<button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Submit</button>
</div></div>
                        </div>
                        <div class="row">
                            
                            
                        <div class="text-center">
                            
                           <!-- <input type="reset" name="Reset" class="btn btn-theme button-1 text-white ctm-border-radius mt-4" /> -->
                        </div>
                    </form>
                </div>
                <div class="col-md-12">
                    <div class="card ctm-border-radius shadow-sm grow">
                        <div class="card-header">
                            <div class="search_emp">
                                <h4 class="card-title mb-0">Manager List</h4>
                                
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive" id="project_list_data">
                                    <table class="table custom-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Manager Name</th>
                                                <th>Employee Code </th>
                                                <th>Manager Email</th>
                                                <th>Technology</th>
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody id="ajaxProjectData">
                                            
                                        @foreach ($managerList as $key=>$project)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ $project->name }}</td>
                                                <td>{{ $project->employee_code }}</td>
                                                <td>{{ $project->email }}</td>
                                                <td>{{ $project->tech_name }}</td>
                                                
                                                
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                   
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                    <div class="card ctm-border-radius shadow-sm grow">
                        <div class="card-header">
                            <div class="search_emp">
                                <h4 class="card-title mb-0">Employee List</h4>
                                
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive" id="project_list_data">
                                    <table class="table custom-table mb-0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Employee Name</th>
                                                <th>Employee Code </th>
                                                <th>Empoyee Email</th>
                                                <th>Technology</th>
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody id="ajaxProjectData">
                                            
                                        @foreach ($empList as $key=>$project)
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ $project->name }}</td>
                                                <td>{{ $project->employee_code }}</td>
                                                <td>{{ $project->email }}</td>
                                                <td>{{ $project->tech_name }}</td>
                                                
                                                
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

 
	})
</script>

@endsection