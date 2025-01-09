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
@foreach ($skills as $skill)
<?php $skillArray[$skill->id] = $skill->tech_name; ?>
@endforeach
<?php $tech = 0;?>
<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Bench Reports</h4>
                </div>
                
                
                        <div class="col-md-12">
                    <div class="card ctm-border-radius shadow-sm grow">
                        <div class="card-header">
                            <div class="search_emp">
                                <h4 class="card-title mb-0">Paid Employee List</h4>
                                
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
                                                <th>Employee Type </th>
                                                <th>Empoyee Email</th>
                                                <th>Technology</th>
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody >
                                        
                                        @foreach ($paidempList as $key=>$project)
                                        
                                        @if($tech != $project->technology_id)
                                        
                                        <tr><th colspan="6" style="text-align:center">{{ $skillArray[$project->technology_id]}}</th></tr>
                                        @endif    
                                        <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ $project->name }}</td>
                                                <td>@if($project->is_paid == 1)
                                                        Paid
                                                    @else
                                                        UnPaid
                                                    @endif</td>
                                                <td>{{ $project->employee_code }}</td>
                                                <td>{{ $project->email }}</td>
                                                <td>{{ $skillArray[$project->technology_id]}}</td>
                                                
                                                
                                            </tr>
                                            <?php $tech = $project->technology_id; ?>
                                        @endforeach
                                        </tbody>
                                    </table>
                                   
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card ctm-border-radius shadow-sm grow">
                        <div class="card-header">
                            <div class="search_emp">
                                <h4 class="card-title mb-0">Non-Paid Employee List</h4>
                                
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
                                                <th>Employee Type </th>
                                                <th>Empoyee Email</th>
                                                <th>Technology</th>
                                                
                                                
                                            </tr>
                                        </thead>
                                        <tbody >
                                            
                                        @foreach ($emplist as $key=>$project)
                                        @if($tech != $project->technology_id)
                                        
                                        <tr><th colspan="6" style="text-align:center">{{ $skillArray[$project->technology_id]}}</th></tr>
                                        @endif
                                            <tr>
                                                <td>{{ $key+1 }}</td>
                                                <td>{{ $project->name }}</td>
                                                <td>@if($project->is_paid == 1)
                                                        Paid
                                                    @else
                                                        UnPaid
                                                    @endif</td>
                                                <td>{{ $project->employee_code }}</td>
                                                <td>{{ $project->email }}</td>
                                                <td>{{ $skillArray[$project->technology_id]}}</td>
                                                
                                                
                                            </tr>
                                            <?php $tech = $project->technology_id; ?>
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