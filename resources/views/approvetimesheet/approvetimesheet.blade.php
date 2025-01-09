@extends('layouts.layout')

@section('content')
<?php
// $monthArray = [date('Y-m-01').','.date('Y-m-t') => date('F'),date("Y-m-01",strtotime("-1 month")).','.date("Y-m-t",strtotime("-1 month"))=>date("F",strtotime("-1 month"))];
$monthArray = [date('Y-m-01').','.date('Y-m-t') => "Current Month ",date("Y-m-01",strtotime("-1 month")).','.date("Y-m-t",strtotime("-1 month"))=>"Prevoius Month"];

?>
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

                    <?php
                    $fiilterData = array();
                    if(!empty(Session::get('filter-timesheetlist'))){
                        if(isset($_GET['emp_id'])){
                            if(Session::get('filter-timesheetlist')['emp_id'] != $_GET['emp_id'] || Session::get('filter-timesheetlist')['week_list'] != $_GET['week_list']){
                                Session::put('filter-timesheetlist', array('emp_id'=>$_GET['emp_id'],'week_list'=>$_GET['week_list']));
                            }
                        }
                    }else{
                        if(isset($_GET['emp_id'])){
                            Session::put('filter-timesheetlist', array('emp_id'=>$_GET['emp_id'],'week_list'=>$_GET['week_list']));
                        }
                    }

                    $checkRedireaction = false;
                    if ((strpos(url()->previous(),'approvetimesheetmass') !== false) && strpos($_SERVER['REQUEST_URI'],'approvetimesheet') !== false) {
                        $checkRedireaction = true;
                    } 

                    if($checkRedireaction && !empty(Session::get('filter-timesheetlist'))){
                        $emp_id = Session::get('filter-timesheetlist')['emp_id'];
                        $week_list = Session::get('filter-timesheetlist')['week_list'];
                        $returnUrl =  url('/')."/approvetimesheetfilter?emp_id=".$emp_id."&week_list=".$week_list;
                    
                    ?>
                    <script>
                        window.location.href = "<?php echo $returnUrl; ?>";
                    </script>
                    <?php } 
                    if(isset($_GET['reset'])){
                        Session::forget('filter-timesheetlist'); ?>
                        <script>
                            window.location.href = "<?php echo url('/')."/approvetimesheet"; ?>";
                        </script>
                    <?php } ?>
                
                    <form action="{{ route('approvetimesheetfilter') }}" method="GET">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-sm-3 col-12">
                                    <div class="form-group">
                                        <label>Developer Name</label>
                                        <select class="form-control select" name="emp_id" id="emp_id">
                                            <option value="" selected>Select Developer</option>
                                            <?php foreach($userData as $data){ ?>
                                                <option value="<?php echo $data->id; ?>" 
                                                    <?php if(isset(Session::get('filter-timesheetlist')['emp_id'])){
                                                        if($data->id == Session::get('filter-timesheetlist')['emp_id']){
                                                        echo "selected";
                                                        }
                                                    }?>
                                                ><?php echo $data->name; ?></option>
                                                <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="form-group">
                                        <label>Weeklist/Month</label>
                                        <select class="form-control select" name="week_list" id="week_list">
                                            <option value="" selected>Select Week/Month</option>
                                            <optgroup label="Month">
                                            <?php foreach($monthArray as $key => $val){?>
                                                    <option value="<?=$key?>" <?php if(isset(Session::get('filter-timesheetlist')['week_list'])){
                                                        if($key == Session::get('filter-timesheetlist')['week_list']){
                                                        echo "selected";
                                                        }
                                                    }?>><?=$val?></option> 
                                                    <?php } ?>
                                                    </optgroup>
                                                    <optgroup label="Week">
                                            <?php foreach($weeklists as $data){  ?>
                                                <option value="<?php echo $data['startvalue'].','.$data['endvalue'];?>" 
                                                    <?php if(isset(Session::get('filter-timesheetlist')['week_list'])){
                                                        if(($data['startvalue'].','.$data['endvalue']) == Session::get('filter-timesheetlist')['week_list']){
                                                        echo "selected";
                                                        }
                                                    }?>
                                                ><?php echo $data['lable']; ?></option>
                                                </optgroup>
                                                <?php } ?>
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
                                        <button type="submit" name="reset" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Reset</button>
                                    </div>
                                </div>

                                <div class="col-sm-1 col-12" style="margin-top: 14px; margin-left: 21px;">
                                    <div class="form-group">
                                        <button type="submit" value="1" name="export" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Export</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php if(count($timesheetData)) { ?>
                    <row>
                        <div class="col-md-12">
                            <div class="timesheetapprove_dv">
                                <div class="col-sm-2 leave-col">
                                    <div class="form-group">
                                        <select id="mass" class="form-control select" name="mass">
                                            <option value="">Select</option>
                                            <option value="Approved">Approved</option>
                                            <option value="ReferBack">ReferBack</option>
                                            <option value="Reject">Reject</option>
                                        </select>
                                    </div>
                                </div>
                                <?php foreach ($timesheetData as $key => $value) { ?>
                                <div class="all_data_dv">
                                    <div class="checkbox_item">
                                        <input type="checkbox" name="checkbox" value="<?php echo $value->id ; ?>" checked>
                                    </div>
                                    <div class="time_list_flex">
                                        <ul>
                                            <div class="time_list_inner_c">
                                                <li><b>Date : </b></li>
                                                <li><?php echo date_format(date_create($value->select_date),"d-m-Y") ; ?></li>
                                            </div>
                                            <div class="time_list_inner_c">
                                                <li><b>Project : </b></li>
                                                <li><?php echo $value->project_name ; ?></li>
                                            </div>
                                            <?php 
                                            if(Auth::user()->role == 4){?>
                                                <div class="time_list_inner_c">
                                                <li><b>Manager : </b></li>
                                                <li><?php echo $manager_data[Auth::user()->id]?></div>
                                            <?php }else{
                                               // print_r($managersArray);
                                                //echo $value->project_id; exit;
                                            ?>
                                            <div class="time_list_inner_c">
                                                <li><b>Manager : </b></li>
                                                <li><?php echo $manager_data[$managersArray[$value->project_id]]?></div>
                                                <?php 
                                                }?>
                                            <div class="time_list_inner_c">
                                                <li><b>Time : </b></li>
                                                <li><?php echo $value->hours."h ".$value->minutes."m" ; ?></li>
                                            </div>
                                            <div class="time_list_inner_c">
                                                <li><b>Status : </b></li>
                                                <li><?php echo $value->status ; ?></li>
                                            </div>
                                            
                                            
                                        </ul>                                       
                                        <div class="timesheetapprove_description_c">
                                            <h4>Description</h4>
                                            <pre><?php echo wordwrap(strip_tags($value->description), 120, "<br/>");?></pre>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group">
                                                    <label>Manager Comment</label>
                                                    <textarea type="text" id="<?php echo $value->id.'_manager_comment' ; ?>" class="form-control" name="manager_comment">{{ $value->manager_comment }}</textarea>
                                                </div>
                                                <div id="error_message_c" class="<?php echo $value->id.'-error-msg' ; ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </row>
                    <?php } ?>
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
        $("#emp_id").select2();
        $('#mass').on('change', function() {
            if(this.value == 'ReferBack' || this.value == 'Reject'){
                var flag = false;
                $("input:checkbox[name=checkbox]:checked").each(function(){
                    var data = '.' + $(this).val() + '-error-msg';
                    var managerCommId = '#' + $(this).val() + '_manager_comment';
                    if(($(managerCommId).val()) == ""){
                        $(data).html("This field is required");
                        flag = true;
                    }
                });
            }
            if(flag == true){
                return false;
            }

            if (confirm("Are you want to "+this.value) == true) {
                let selectedIds =[];
                let managerComment =[];
                $("input:checkbox[name=checkbox]:checked").each(function(){
                    var managerCommId = '#' + $(this).val() + '_manager_comment';
                    managerComment.push($(managerCommId).val());
                    selectedIds.push($(this).val());
                });

                $.ajax({
                    url: "{{ route('approvetimesheetmass') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "selectedIds":selectedIds,
                        "managerComment":managerComment,
                        "status" : this.value
                    },
                    dataType: "text",
                    success: function (data) {
                        window.location = "{{ route('approvetimesheetfilter') }}";
                    }
                });
            }
        });
    });
</script>

@endsection