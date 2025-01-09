@extends('layouts.layout')

@push('style')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    span.select2.select2-container.select2-container--classic{
        width: 100% !important;
    }
</style>
@endpush

<style>
.search_pro {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}
.proser_sec {
    display: flex;
    align-items: center;
    gap: 10px;
}
.proser_sec button {
    margin-top: 0px !important;
}
.search_pro a {
    margin-top: -16px !important;
}
.proser_sec input {
    height: 40px;
}
</style>

@section('content')
<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <div class="search_pro">

                            <?php
                            $fiilterData = array();
                            if(!empty(Session::get('filter-assignproject'))){
                                if(isset($_GET['search'])){
                                    if(Session::get('filter-assignproject')['search'] != $_GET['search'] || (isset($_GET['page']) && Session::get('filter-assignproject')['page'] != $_GET['page'])){
                                        $page = 0;
                                        if(isset($_GET['page']) && $_GET['page']!=''){
                                        $page=$_GET['page'];
                                        }
                                        Session::put('filter-assignproject', array('search'=>$_GET['search'],'page'=>$page));
                                    }
                                    
                                }
                            }else{
                                if(isset($_GET['search'])){
                                    $page = 0;
                                    if(isset($_GET['page']) && $_GET['page']!=''){
                                    $page=$_GET['page'];
                                    }
                                    Session::put('filter-assignproject', array('search'=>$_GET['search'],'page'=>$page));
                                }
                            }

                            $checkRedireaction = false;
                            if (strpos(url()->previous(),'projectmanagerupdate') !== false || strpos($_SERVER['REQUEST_URI'],'projectmanager') !== false) {
                                $checkRedireaction = true;
                            } 

                            if($checkRedireaction && !empty(Session::get('filter-assignproject'))){
                                $search = Session::get('filter-assignproject')['search'];
                                $page = Session::get('filter-assignproject')['page'];
                                $returnUrl =  url('/')."/assignprojectfilter?search=".$search."&page=".$page;
                            
                            ?>
                            <script>
                                window.location.href = "<?php echo $returnUrl; ?>";
                            </script>
                            <?php } 
                            if(isset($_GET['reset'])){
                                Session::forget('filter-assignproject'); ?>
                                <script>
                                    window.location.href = "<?php echo url('/')."/projectmanager"; ?>";
                                </script>
                            <?php } ?>

                        <h4 class="card-title mb-0">Assign Project</h4>
                        <form action="{{ route('assignprojectfilter') }}" method="GET" autocomplete="off">
                            <div class="proser_sec">
                                <input type="text" id="search" class="form-control" name="search" placeholder="search"
                                    value="<?php
                                        if(isset($_GET['search']) && ($_GET['search']) != ''){
                                        echo $_GET['search'];
                                        }
                                    ?>"
                                >
                                <button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4 super_search">Search</button>
                                <button type="submit" name="reset" class="btn btn-theme button-1 text-white ctm-border-radius mt-4 super_search">Reset</button>
                            </div>
                        </form>
                    </div>
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
                    <form action="{{ route('projectmanagersave') }}" method="POST">
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
                                        @foreach ($project as $projects)
                                        <option value="{{ $projects->id }}">{{ $projects->project_name }}</option>
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
                                        Manager Name
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select" name="manager_id" id="manager_id">
                                        <option value="">Select</option>
                                        @foreach ($managers as $manager)
                                        <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('manager_id'))
                                <p class="text-danger">{{ $errors->first('manager_id') }}</p>
                                @endif	
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>
                                        Employee Name
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select developer_id" id="developer_id" name="developer_id">
                                        <option value="">Select</option>
                                        @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('developer_id'))
                                <p class="text-danger">{{ $errors->first('developer_id') }}</p>
                                @endif	
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Technology<span class="text-danger">*</span></label>
                                    <select class="form-control select" name="technology_id">
                                        <option value="">Select</option>
                                        @foreach ($allTechnology as $technology)
                                        <option value="{{ $technology->id }}">{{ $technology->tech_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('technology_id'))
                                <p class="text-danger">{{ $errors->first('technology_id') }}</p>
                                @endif	
                            </div>	
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>
                                        Status
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-control select" name="status">
                                        <option value="1">Active</option>
                                        <option value="0">Deactivate</option>
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
                            <h4 class="card-title mb-0">Assign Project List</h4>
                        </div>
                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive">
                                    <table class="table custom-table mb-0 project">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Manager Name</th>
                                                <th>Project Name</th>
                                                <th>Developer Name</th>
                                                <th>Technology Name</th>
                                                <th>Status</th>
                                                <th width="280px">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i=0;
                                            foreach ($proj_dev_list as $projectmanager){
                                                $i++;
                                            ?>
                                            <tr>
                                                <td><?php echo $i; ?></td>
                                                <td><?php echo isset($managerdatas[$projectmanager->manager_id])? $managerdatas[$projectmanager->manager_id] : ''; ?></td>
                                                <td><?php echo $projectmanager->project_name; ?></td>
                                                <td><?php echo $projectmanager->name; ?></td>
                                                <td><?php echo $projectmanager->tech_name; ?></td>
                                                <td>
                                                    <?php if($projectmanager->status == 1){ 
                                                        echo "Active";
                                                    }else{
                                                        echo "Deactive";
                                                    }?>
                                                </td>
                                                <td>
                                                    <?php if($projectmanager->status == 0){ ?>
                                                        <a onclick="return confirm('Are you want to Activate?')" href="{{ url('projectmanagerupdate/'.$projectmanager->id.'/1') }}">Activate</a>
                                                <?php }else{ ?>
                                                        <a onclick="return confirm('Are you want to Deactive?')" href="{{ url('projectmanagerupdate/'.$projectmanager->id.'/0') }}">Deactivate</a>
                                                <?php }?>
                                                </td>
                                            </tr>
                                            <?php } ?>
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

<!-- Select2 CSS --> 
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" /> 

<!-- jQuery --> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 

<!-- Select2 JS --> 
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
$(document).ready(function(){
 
 // Initialize select2
 $("#developer_id").select2();
 $("#project_id").select2();
 $("#manager_id").select2();

});
</script>

@endsection


