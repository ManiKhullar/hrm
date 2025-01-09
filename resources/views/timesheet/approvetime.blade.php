@extends('layouts.layout')

@section('content')

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<style>
    .tooltip {
        position: relative;
        display: inline-block;
        opacity: inherit;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 300px;
        background-color: black;
        color: #fff;
        text-align: center;
        border-radius: 6px;
        padding: 5px 0;

        /* Position the tooltip */
        position: absolute;
        z-index: 1;
        right: 0px;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
    }

/* table.table.custom-table tbody tr td:nth-child(7) {
    -webkit-line-clamp: 1;
    -webkit-box-orient: vertical;
    width: 60px;
    display: block;
    text-overflow: ellipsis;
    word-wrap: break-word;
    overflow: hidden;
    max-height: 3.6em;
    line-height: 1.8em;
} */
.time_log_list_p {
    text-align: center;
    padding: 10px;
    border-radius: 4px;
    color: #000;
    margin-top: 15px;
    text-transform: capitalize;
}
#rst{float:right !important;margin-top: 36px !important;}
#sbt{margin-top: 36px !important;}
</style>

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
                    /*$fiilterData = array();
                    if(!empty(Session::get('filter-approvetime'))){
                        if(isset($_GET['project_name']) && isset($_GET['user_id'])){
                            if(Session::get('filter-approvetime')['project_name'] != $_GET['project_name'] || Session::get('filter-approvetime')['user_id'] != $_GET['user_id'] || Session::get('filter-approvetime')['from'] != $_GET['from'] || Session::get('filter-approvetime')['to'] != $_GET['to'] || (isset($_GET['page']) && Session::get('filter-approvetime')['page'] != $_GET['page'])){
                                $page = 0;
                                if(isset($_GET['page']) && $_GET['page']!=''){
                                    $page=$_GET['page'];
                                }
                                Session::put('filter-approvetime', array('project_name'=>$_GET['project_name'],'user_id'=>$_GET['user_id'],'from'=>$_GET['from'],'to'=>$_GET['to'],'page'=>$page));
                            } 
                        }
                    }else{
                        if(isset($_GET['project_name']) && isset($_GET['user_id'])){
                            $page = 0;
                            if(isset($_GET['page']) && $_GET['page']!=''){
                                $page=$_GET['page'];
                            }
                            Session::put('filter-approvetime', array('project_name'=>$_GET['project_name'],'user_id'=>$_GET['user_id'],'from'=>$_GET['from'],'to'=>$_GET['to'],'page'=>$page));
                        }
                    }

                    if(isset($_GET['reset'])){
                        Session::forget('filter-approvetime'); ?>
                        <script>
                            window.location.href = "<?php echo url('/')."/approvetime"; ?>";
                        </script>
                    <?php }

                    $checkRedireaction = false;
                    if (((strpos(url()->previous(),'approvetimeedit') !== false) && (strpos($_SERVER['REQUEST_URI'],'approvetime') !== false)) || ((strpos(url()->previous(),'approvetimefilter') !== false) && (strpos($_SERVER['REQUEST_URI'],'approvetime') !== false))) {
                        $checkRedireaction = true;
                    }
                    if($checkRedireaction && !empty(Session::get('filter-approvetime'))){
                        $project_name = Session::get('filter-approvetime')['project_name'];
                        $user_id = Session::get('filter-approvetime')['user_id'];
                        $from = Session::get('filter-approvetime')['from'];
                        $to = Session::get('filter-approvetime')['to'];
                        $page = Session::get('filter-approvetime')['page'];
                        $returnUrl =  url('/')."/approvetimefilter?project_name=".$project_name."&user_id=".$user_id."&from=".$from."&to=".$to."&page=".$page;
                    
                    ?>
                    <script>
                        window.location.href = "<?php echo $returnUrl; ?>";
                    </script>
                    <?php } */ ?>

                    <form action="{{ route('approvetimefilter') }}" method="GET">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-sm-3 col-12">
                                    <div class="form-group">
                                        <label>Project Name</label>
                                        <select class="form-control select" name="project_name">
                                            <option value="">Select Project</option>
                                            @foreach ($project_managers as $project_manager)
                                                @foreach ($projects as $project)
                                                    @if ($project_manager->project_id == $project->id)
                                                        
                                                            <option value="{{ $project->id }}" <?php if(isset($_GET['project_name']) && $_GET['project_name'] != $project->id) echo 'selected' ?>>{{ $project->project_name }}</option>
                                                        
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-2 col-12">
                                    <div class="form-group">
                                        <label>Developer Name</label>
                                        <select class="form-control select" name="user_id">
                                            <option value="">Select Developer</option>
                                            @foreach ($developers as $developer)
                                                @foreach ($userData as $user)
                                                    @if ($developer->developer_id == $user->id)
                                                        
                                                <option value="{{ $user->id }}" <?php if(isset($_GET['user_id']) && $_GET['user_id'] == $user->id ) echo 'selected'  ?>>{{ $user->name }}</option>
                                            
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('department'))
                                    <p class="text-danger">{{ $errors->first('department') }}</p>
                                    @endif
                                </div>
                                <div class="col-sm-2 col-12">
                                    <div class="form-group">
                                        <label>From</label>
                                        <input required type="text" id="from" class="form-control" name="from" 
                                            value="<?php 
                                                if(isset($_GET['from']) && $_GET['from'] != ''){ 
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
                                <div class="col-sm-2 col-12">
                                    <div class="form-group">
                                        <label>To</label>
                                        <input type="text" required id="to" class="form-control" name="to"
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
                                <div class="col-sm-3 col-12">
                                    
                                        <button type="submit" id="sbt" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Submit</button>
                                    
                                    
                                        <button type="button" id="rst" name="reset" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Reset</button>
                                   
                                </div>
                               <!-- <div class="col-sm-1 col-12">
                                    <div class="form-group">
                                        </div>
                                </div> -->
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-12">
                    <div class="card ctm-border-radius shadow-sm grow">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Time log List</h4>
                        </div>
                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive">
                                    <form>
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
                                        <table class="table custom-table mb-0">
                                            <tr>
                                                <th><input id="allchk" type="checkbox" onchange="checkAll(this)" name="chk[]" /></th>
                                                <th>No</th>
                                                <th>Project Name</th>
                                                <th>Developer Name</th>
                                                <th>Hours</th>
                                                <th>Log Date</th>
                                                <th>Description</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                            <?php $i = 0;
                                           if (count($timesheets)){ ?>
                                                @foreach ($timesheets as $timesheet)
                                                    <tr>
                                                        <td><input type="checkbox" name="checkbox" value="{{ $timesheet->id }}"></td>
                                                        <td>{{ ++$i }}</td>
                                                        <td>{{ $timesheet->project_name }}</td>
                                                        <td>{{ $timesheet->name }}</td>
                                                        <td>{{ $timesheet->hours }}h {{ $timesheet->minutes }}m</td>
                                                        <td><?php echo date("d/m/Y",strtotime($timesheet->select_date)) ?></td>
                                                        <td>
                                                        
                                                            <div class="tooltip"><?php echo strip_tags(substr($timesheet->description,0,15)); ?>
                                                                <span class="tooltiptext"><? echo strip_tags($timesheet->description); ?></span>
                                                            </div>
                                                        </td>
                                                        <td>{{ $timesheet->status }}</td>
                                                        <td>
                                                            <a href="{{ url('approvetimeedit/'.$timesheet->id.'/edit') }}">
                                                                <i style='font-size:24px' class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>
                                            <?php }else { ?>
                                            </table>
                                                <p class="time_log_list_p" >No record found</p>
                                            <?php } ?>
                                            {!! $timesheets->links() !!}
                                    </form>
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
    $(document).ready(function() {
     //   $("#from"). prop("readonly", true); 
      //  $("#to"). prop("readonly", true); 
    });
</script>
<script>
    $(document).ready(function() {
        $('#allchk').click(function() {
            $('input[type="checkbox"]').prop('checked', this.checked);
        })
    });
</script>
<script>
    $(document).ready(function(){
        $('#rst').on('click',function(e){
            window.location.href="/approvetimefilter";
        })
        $('#mass').on('change', function() {
            if (confirm("Are you want to "+this.value) == true) {
                let selectedIds =[];
                $("input:checkbox[name=checkbox]:checked").each(function(){
                    selectedIds.push($(this).val());
                });
                if(selectedIds.length === 0){
                    confirm("The checked value can not be blank.");
                    return;
                }
                $.ajax({
                    url: "{{ route('approvetimemass') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "selectedIds":selectedIds,
                        "status" : this.value
                    },
                    dataType: "text",
                    success: function (data) {
                        window.location = "{{ route('approvetime') }}";
                    }
                });
            }
        });
    });
</script>

@endsection