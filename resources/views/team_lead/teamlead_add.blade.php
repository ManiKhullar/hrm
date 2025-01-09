@extends('layouts.layout')

@section('content')

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-body">
                    @if (Session::has('error'))
                    <p class="text-danger">{{ Session::get('error') }}</p>
                    @endif
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                    @endif

                
                    <form action="{{ route('add_teamlead') }}" method="post">
                    @csrf
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-sm-3 col-12">
                                    <div class="form-group">
                                        <label>Team Leads</label>
                                        <select class="form-control" required name="emp_id" id="emp_id">
                                            <option value="" selected>Select Developer</option>
                                            <?php foreach($userData as $data){ ?>
                                                <option value="<?php echo $data->id; ?>"><?php echo $data->name; ?></option>
                                                <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="form-group">
                                        <label>Team Member</label>
                                        <select class="form-control" style="height:150px !important;" required multiple="multiple" name="user_id[]" id="user_id">
                                            <optgroup  label="Select Developer" >
                                            <?php foreach($userData as $data){ ?>
                                                <option value="<?php echo $data->id; ?>"><?php echo $data->name; ?></option>
                                                <?php } ?>
                                                </optgroup>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-1 col-12" style="margin-top: 14px;">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Submit</button>
                                    </div>
                                </div>
                                <div class="col-sm-1 col-12" style="margin-top: 14px; margin-left: 21px;">
                                    <div class="form-group">
                                        <button type="reset" id="resetbtn" name="reset" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php  if(count($team_lead)) { ?>
                    <row>
                        <div class="col-md-12">
                            <div class="timesheetapprove_dv">
                            
                                <?php foreach ($team_lead as $key => $value) { ?>
                                <div class="all_data_dv">
                                    
                                    <div class="time_list_flex">
                                        <ul>
                                            <div class="time_list_inner_c">
                                                <li><b>Team Lead : </b></li>
                                                <li><?php echo $value->name ; ?></li>
                                            </div>
                                            <div class="time_list_inner_c">
                                                <li><b>Team Member : </b></li>
                                            <li> 
                                                <?php foreach($teamMember as $member){
                                                    if($value->id == $member->teamlead_id){?>
                                                    <?php echo $member->name ; ?> <br />
                                            <?php } 
                                            }   ?>
                                                 </li>
                                            </div>
                                            <div class="time_list_inner_c">
                                                <li><b>Status : </b></li>
                                                <li><?php if($value->status > 0)
                                            echo "Enable "; 
                                            else 
                                            echo"Disable ";?></li>
                                            </div>
                                            <div class="time_list_inner_c">
                                                <li><b>Action : </b></li>
                                                <li><a href="/delete_team_lead/<?=$member->teamlead_id?>" title="Delete"><?php echo " Delete";?></a></li>
                                            </div>
                                            
                                        </ul>
                                       
                                       
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </row>
                    <?php }  ?>

            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .justify-between {
        width: 75px;
    }
 
.timesheetapprove_description_c h4 {
    font-size: 16px;
    font-weight: 700;
    padding: 15px 0px;
    margin: 0px;
}
.timesheetapprove_dv {
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 20px;
}
.timesheetapprove_description_c {
    padding: 10px 0px;
    border-radius: 8px;
}

.time_list_flex ul {
    margin: 0px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 0px;
    
}
.time_list_flex{
    border-radius: 8px;
    padding: 20px;
    width: 100%;
}
.timesheetapprove_dv .all_data_dv {
    display: flex;
    gap: 20px;
    align-items: center;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}
.checkbox_item input {
    width: 20px;
    height: 20px;
}
.time_list_flex ul .time_list_inner_c li b {
    font-weight: 700;
    font-size: 1rem;
}
.time_list_flex ul .time_list_inner_c {
    display: flex;
    gap: 10px;
    align-items: center;
}
.time_list_flex .form-group label {
    font-weight: 700;
    font-size: 16px;
}
#error_message_c {
    color: #f00;
    font-weight: 400;
    text-transform: capitalize;
    font-size: 14px;
}
.timesheetapprove_description_c pre {
    height: 200px;
    overflow: auto;
    height: max-content;
    font-size: 15px;
}

</style>

<script>
    $(document).ready(function(){
        //$("#emp_id").select2();
        $('#mass').on('change', function() {
            
            
            
        });
    });
    $('#resetbtn').on('click',function(){
        $('#user_id').val(null).trigger('change');
        $('#emp_id').val(null).trigger('change');
   //window.location.href="/team_lead";
});

</script>

@endsection