@extends('layouts.layout')

@section('content')
<?php
$time = mktime(0,0,0,date('m'),5,date('Y'));
$day = date('d') - 1;
?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<style>
    .tooltip {
        position: relative;
        display: inline-block;
        opacity: inherit;
        z-index: 0;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 300px;
        background-color: black;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;
        position: absolute;
        z-index: 1;
        right: 100%;
        bottom: 0;
    }
    .tooltip:hover .tooltiptext {
        visibility: visible;
        opacity: 1;
    }

    .time_log_list_d {
        text-align: center;
        padding: 10px;
        border-radius: 4px;
        color: #000;
        margin-top: 15px;
        text-transform: capitalize;
    }
    /* The Modal (background) */
    .modal {
        display: none; /* Hidden by default */
        position: fixed; /* Stay in place */
        z-index: 1; /* Sit on top */
        padding-top: 100px; /* Location of the box */
        left: 0;
        top: 0;
        width: 100%; /* Full width */
        height: 100%; /* Full height */
        overflow: auto; /* Enable scroll if needed */
        background-color: rgb(0,0,0); /* Fallback color */
        background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
    }

    /* Modal Content */
    .modal-content {
        background-color: #fefefe;
        margin: auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
    }

    /* The Close Button */
    .close {
        color: #aaaaaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        text-align:right;
    }

    .close:hover, .close:focus {
        color: #000;
        text-decoration: none;
        cursor: pointer;
    }
    .multiple_btn{
        padding: 2px 23px;
        background: #6366f1;
        background-image: linear-gradient(to top, #D8D9DB 0%, #fff 80%, #FDFDFD 100%);
        border-radius: 30px;
        border: 1px solid #8F9092;
        box-shadow: 0 4px 3px 1px #FCFCFC, 0 6px 8px #D6D7D9, 0 -4px 4px #CECFD1, 0 -6px 4px #FEFEFE, inset 0 0 3px 0 #CECFD1;
        transition: all 0.2s ease;
        font-family: "Source Sans Pro", sans-serif;
        font-size: 14px;
        font-weight: 600;
        color: #606060;
        text-shadow: 0 1px #fff;
        margin: 0px 5px;
    }
    .multi_dlex {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center
    }

    .modal-content.time_seet_popup {
        width: 65%;
        top: -10%;
    }
   
    @media screen and (max-width: 767px) {
        .multi_dlex {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .modal-content.time_seet_popup {
            width: 95%;
        }
        .ctm-border-radius .card-body .uniq_btn_item {
    text-align: left;
    width: 100%;
}
.ctm-border-radius .card-body .uniq_btn_item input {
    margin: 0 1px;
    outline: none;
    background: transparent linear-gradient(309deg, #E0EDF9 0%, #F3F0FC 100%) 0% 0% !important;
    box-shadow: none;
    border-radius: 6px;
    letter-spacing: 0.5px;
    width: 100%;
    box-sizing: border-box;
    padding: 3px 2px 2px 3px;
}
    }

    .cke_notifications_area {
        display: none;
    }
    @media screen and (min-width: 320px) and (max-width: 767px) {
.multi_dlex {
    margin-left: 12px;
}
.multi_dlex .uniq_btn_item.uniq_btn input {
    color: #000 !important;
    width: 100%;
}.ctm-border-radius .card-body .uniq_btn_item input {
    padding: 5px 0px;
    margin: 0 1px;
    outline: none;
    background: transparent linear-gradient(309deg, #E0EDF9 0%, #F3F0FC 100%) 0% 0% !important;
    box-shadow: none;
    border-radius: 6px;
    letter-spacing: 0.5px;
}
	.multiple_btn {
    padding: 2px 23px;
    background: #6366f1;
    background-image: linear-gradient(to top, #D8D9DB 0%, #fff 80%, #FDFDFD 100%);
    border-radius: 30px;
    border: 1px solid #8F9092;
    box-shadow: 0 4px 3px 1px #FCFCFC, 0 6px 8px #D6D7D9, 0 -4px 4px #CECFD1, 0 -6px 4px #FEFEFE, inset 0 0 3px 0 #CECFD1;
    transition: all 0.2s ease;
    font-family: "Source Sans Pro", sans-serif;
    font-size: 12px !important;
    font-weight: 600;
    color: #606060;
    text-shadow: 0 1px #fff;
    margin: 0px 5px;
}
}    
</style>
<script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add Time Sheet</h4>
                </div>
                <div class="card-body">
					<div class="flash-message">
                        <p class="alert alert-danger" id="duplicate_two" style="display:none">Duplicate entry or you have already applied for leave! <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                        <p class="alert alert-danger" id="invalid_zero" style="display:none">Invalide Date! <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                        <p class="alert alert-success" id="success_one" style="display:none">Timesheet Created Successfully.! <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
                    </div>
                    @if (Session::has('error'))
                    <p class="text-danger">{{ Session::get('error') }}</p>
                    @endif
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                    @endif
                    <form id="myform" autocomplete="off">
                        @csrf
                        <div id="myModal" class="modal">
                            <input type="hidden" class="form-control" id="project_id" name="project_id">

                            <!-- Modal content -->
                            <div class="modal-content time_seet_popup">
                                <span class="close" id="close_win">&times;</span>
                                <div class="error-msg">

                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-12">
                                        <div class="form-group">
                                            <label>Select Date</label>
                                            <input readonly type="text" id="select_date" class="form-control datepicker" name="select_date">
                                        </div>
                                        @if ($errors->has('select_date'))
                                        <p class="text-danger">{{ $errors->first('select_date') }}</p>
                                        @endif
                                    </div>
                                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                        <div class="row">
                                            <div class="col-sm-6 col-6 leave-col">
                                                <div class="form-group">
                                                    <label>Time (In Hours)</label>
                                                    <select class="form-control select" id="hours" name="hours">
                                                        <?php 
                                                        foreach ($hours as $key => $hour) { ?>
                                                        <option value="{{ $key }}">{{ $hour }}</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                @if ($errors->has('hours'))
                                                <p class="text-danger">{{ $errors->first('hours') }}</p>
                                                @endif
                                            </div>
                                            <div class="col-sm-6 col-6 leave-col">
                                                <div class="form-group">
                                                    <label>Time (In Minute)</label>
                                                    <select class="form-control select" id="minutes" name="minutes">
                                                        <?php 
                                                        foreach ($minutes as $key => $minute) { ?>
                                                        <option value="{{ $key }}">{{ $minute }}</option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                @if ($errors->has('minutes'))
                                                <p class="text-danger">{{ $errors->first('minutes') }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>	
                                   
                                </div>
                                <div class="row">
                                    <div class="col-sm-12 col-12">
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea class="form-control" id="description" name="description"></textarea>
                                        </div>
                                        @if ($errors->has('description'))
                                        <p class="text-danger">{{ $errors->first('description') }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="text-center">
                                    <p id="button" type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Submit</p>
                                </div>
                            </div>
                        </div>
                    </form>
                <div class="row">
                    <div class="col-sm-12 leave-col">
                        <div class="form-group">
                            <div class="multi_dlex">
                            <label>Assigned Projects</label>
                            @foreach ($projects as $project)
                            <div class="uniq_btn_item uniq_btn">
                                <input type="submit" onclick="myFunction({{ $project->id }})" class="multiple_btn" id="{{ $project->id }}" value="{{ $project->project_name }}">
                            </div>
                            @endforeach
                            </div>
                        </div>	
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card ctm-border-radius shadow-sm grow">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Time Sheet List</h4>
                        </div>

                        <form action="{{ route('timesheetfilter') }}" method="GET">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-6 leave-col">
                                        <div class="form-group">
                                            <label>Project Name</label>
                                            <select class="form-control select" name="project_name">
                                                <option value="">Select Project</option>
                                                @foreach ($projects as $project)
                                                <?php if(isset($_GET['project_name']) && ($_GET['project_name']) != ''){ ?>
                                                    <option value="{{ $project->id }}" <?php echo $_GET['project_name']  == $project->id ? 'selected' : '' ?>>{{ $project->project_name }}</option>
                                                <?php }else{ ?>
                                                    <option value="{{ $project->id }}">{{ $project->project_name }}</option>
                                                <?php } ?>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-6 leave-col">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select class="form-control select" name="status">
                                                <option value="">Select Status</option>
                                                <option value="Approved">Approved</option>
                                                <option value="ReferBack">ReferBack</option>
                                                <option value="Reject">Reject</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-6 col-6 leave-col">
                                        <div class="form-group">
                                            <label>From</label>
                                            <input type="text" id="from" class="form-control" name="from" 
                                            value="<?php 
                                                    if(isset($_GET['from']) && ($_GET['from']) != ''){ 
                                                        $date = strtotime($_GET['from']); 
                                                        echo date('m/d/Y', $date);
                                                    }else{
                                                        $cureent_date = date('Y-m-d'); 
                                                        $date = strtotime($cureent_date.'-1 month'); 
                                                        echo date('m/d/Y', $date);
                                                    }
                                                     
                                                ?>"
                                            >
                                        </div>
                                        @if ($errors->has('from'))
                                        <p class="text-danger">{{ $errors->first('from') }}</p>
                                        @endif
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-6 col-6 leave-col">
                                        <div class="form-group">
                                            <label>To</label>
                                            <input type="text" id="to" class="form-control" name="to" 
                                            value="<?php
                                                if(isset($_GET['to']) && ($_GET['to']) != ''){
                                                    $date = strtotime($_GET['to']); 
                                                    echo date('m/d/Y', $date);
                                                }else{
                                                    echo date('m/d/Y');
                                                }
                                                ?>"
                                            >
                                        </div>
                                        @if ($errors->has('to'))
                                        <p class="text-danger">{{ $errors->first('to') }}</p>
                                        @endif
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-6 col-6 leave-col">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive">
                                    <table class="table custom-table mb-0 veiw_tab">
                                        <tr>
                                            <th>No</th>
                                            <th>Project Name</th>
                                            <th>Hours</th>
                                            <th>Log Date</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                        <?php if(count($timesheets)) {?>
                                            @foreach ($timesheets as $timesheet)
                                                <tr>
                                                    <td>{{ ++$i }}</td>
                                                    <td>{{ $timesheet->project_name }}</td>
                                                    <td>{{ $timesheet->hours }}h {{ $timesheet->minutes }}m</td>
                                                    <td><?php echo date("d/m/Y",strtotime($timesheet->select_date)); ?></td>
                                                    <td class="description_tooltip">
                                                        <div class="tooltip"><?php  echo strip_tags(trim(substr($timesheet->description,0,15))); ?>
                                                            <div class="tooltiptext"><?php echo strip_tags( $timesheet->description); ?></div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $timesheet->status }}</td>
                                                    <td>@if($timesheet->status == "ReferBack" || $timesheet->status == "Pending")
                                                        <a href="{{ url('timesheetedit/'.$timesheet->id.'/edit') }}"><i style='font-size:24px' class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                                        @endif
                                                        <a href="{{ url('timesheetview/'.$timesheet->id.'/view') }}"><i style='font-size:24px' class="fa fa-eye" aria-hidden="true"></i></a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                        {!! $timesheets->links() !!}
                                        <?php }else { ?>
                                        </table>
                                            <p class="time_log_list_d" >No record found</p>
                                        <?php } ?>
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
<?php 
//echo Auth::user()->role;
if(Auth::user()->role ==4 ){?>
    <script>
    $(function() {
        var days = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"];
        
        
        $( "#select_date" ).datepicker({
            maxDate: 0,
            minDate : "-60d",
            beforeShowDay: function( date ) {
                var day = date.getDay();
                return [ ( day > 0 && day < 6 ), "" ];
            }
        });
        
    });
    </script>
<?php }elseif($time > time()){?>
<script>
    $(function() {
        var days = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"];
        
        
        $( "#select_date" ).datepicker({
            maxDate: 0,
            minDate : "-30d",
            beforeShowDay: function( date ) {
                var day = date.getDay();
                return [ ( day > 0 && day < 6 ), "" ];
            }
        });
        
    });
    </script>
    <?php } else{?>
        <script>
    $(function() {
        var days = ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"];
        
        
        $( "#select_date" ).datepicker({
            maxDate: 0,
            minDate : "-<?=$day?>d",
            beforeShowDay: function( date ) {
                var day = date.getDay();
                return [ ( day > 0 && day < 6 ), "" ];
            }
        });
        
    });
    </script>
        <?php } ?>
    <script>
    function myFunction(btnId){
        // Get the modal
        var modal = document.getElementById("myModal");
        modal.style.display = "block";

        // Get the button that opens the modal
        document.getElementById('project_id').value = btnId;

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    }
    $('.ui-datepicker-prev').ready(function(){
		$('.ui-datepicker-prev').hide();
		})
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        CKEDITOR.replace('description');
    });
</script>
<script>
$(document).ready(function(){
$( "#select_date" ).on('change',function(){
	$( "#select_date" ).attr('readonly', true);
	var reportDate = $( "#select_date" ).val();
	var currentMonth = new Date().getMonth();
	var todayDate = new Date().getDate();
	var reportMonth = new Date(reportDate).getMonth();
	var reportDay = new Date(reportDate).getDate();
	
	})
    $('#myform').on('click', '#button', function() {
        var date = $("#select_date").val();
        var hours = $("#hours").val();
        var minutes = $("#minutes").val();
        var desc = $("#description").val();
        var project_id = $("#project_id").val();
        var editorContent = CKEDITOR.instances.description.getData();
        if((date == "") || ((hours == "00") && (minutes == "00")) ) {
            $(".error-msg").html("All fields is required");
            return false;
        }else{
            $(".error-msg").html("");
        }
        $('#myModal').hide();
        
        $.ajax({
            url: "{{ route('timesheetsave') }}",
            type: "POST",
            data: {
					"_token": "{{ csrf_token() }}",
					'select_date':date,
                    'hours':hours,
                    'minutes':minutes,
                    'description':editorContent,
                    'project_id':project_id
                },
            dataType: "html",
            success: function (data) {
            
				if(data == "1"){
					$('#success_one').show();
					$('#duplicate_two').hide();
					$('#invalid_zero').hide();
				}else if(data == "2"){
					$('#success_one').hide();
					$('#duplicate_two').show();
					$('#invalid_zero').hide();
				}else{
					$('#invalid_zero').show();
					$('#duplicate_two').hide();
					$('#success_one').hide();
				}
					//return false;
					// $('#myModal').hide();
					//return false;
				setTimeout(function(){ window. location. reload(); }, 2000);
				return false;
            }
        });
    });
});
</script>
<script>
    $(function() {
        $( "#from" ).datepicker({ 
            changeYear: true,
            minDate: '-3Y'
        });
        $( "#to" ).datepicker({ 
            changeYear: true,
            minDate: '-3Y'
        });
    });
    $('#close_win').on('click',function(e){
        $('#myModal').hide();
        $('#project_id').val('');
       $('#select_date').val('');
        $('#select2-hours-container').html('00');
        $('#select2-minutes-container').html('00');
       CKEDITOR.instances.description.setData('');
    });
</script>


@endsection
