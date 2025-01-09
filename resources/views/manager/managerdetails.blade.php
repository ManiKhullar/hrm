@extends('layouts.layout')

@section('content')

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-body">
                    <form action="{{ route('managerdetailsfilter') }}" method="GET">
                        <div class="row">	
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label>Manager List<span class="text-danger">*</span></label>
                                    <select class="form-control select" name="manager_id" id="manager_id">
                                        <option value="">Select</option>
                                        @foreach ($managers as $manager)
                                        <option value="{{ $manager->id }}"
                                            <?php if(isset($_GET['manager_id'])){
                                                if($manager->id == $_GET['manager_id']){
                                                    echo 'selected';
                                                }
                                            }?>
                                        >{{ $manager->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if ($errors->has('manager_id'))
                                <p class="text-danger">{{ $errors->first('manager_id') }}</p>
                                @endif	
                            </div>
                            <div class="text-center" style="margin-top:10px">
                                <button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-12">
                    <div class="card ctm-border-radius shadow-sm grow">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Manager Details</h4>
                        </div>
                        <div class="card-body">
                            <?php if(count($projectlistdata)) { ?>
                                <div class="employee-office-table">
                                    <div class="table-responsive">
                                        <table class="table custom-table mb-0 project">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Manager Name</th>
                                                    <th>Project Name</th>
                                                    <th>Developer Name</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i = 0;
                                                foreach ($projectlistdata as $projectmanager){ ?>
                                                    <tr>
                                                        <td><?php echo ++$i; ?></td>
                                                        <td><?php echo isset($managerdatas[$projectmanager->manager_id])? $managerdatas[$projectmanager->manager_id] : ''; ?></td>
                                                        <td><?php echo $projectmanager->project_name; ?></td>
                                                        <td><?php echo $projectmanager->name; ?></td>
                                                        <td>
                                                            <?php if($projectmanager->status == 1){
                                                                echo "Active";
                                                            }else{
                                                                echo "Deactive";
                                                            }?>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <p class="project_list" >No record found</p>
                            <?php } ?>
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

    .project_list {
        text-align: center;
        padding: 10px;
        border-radius: 4px;
        color: #000;
        margin-top: 15px;
        text-transform: capitalize;
    }
</style>

<script>
$(document).ready(function(){
 
 // Initialize select2
    $("#manager_id").select2();

});
</script>

@endsection


