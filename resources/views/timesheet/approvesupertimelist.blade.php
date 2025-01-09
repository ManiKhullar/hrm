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
        right: 100%;
    }

    .tooltip:hover .tooltiptext {
        visibility: visible;
    }

    .time_log_list_p {
        text-align: center;
        padding: 10px;
        border-radius: 4px;
        color: #000;
        margin-top: 15px;
        text-transform: capitalize;
    }
    
    .timesheet_export {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
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
                    $fiilterData = array();
                    if(!empty(Session::get('filter-timesheet'))){
                        if(isset($_GET['emp_id'])){
                            if(Session::get('filter-timesheet')['emp_id'] != $_GET['emp_id'] || Session::get('filter-timesheet')['from'] != $_GET['from'] || Session::get('filter-timesheet')['to'] != $_GET['to'] || (isset($_GET['page']) && Session::get('filter-timesheet')['page'] != $_GET['page'])){
                                $page = 0;
                                if(isset($_GET['page']) && $_GET['page']!=''){
                                $page=$_GET['page'];
                                }
                                Session::put('filter-timesheet', array('emp_id'=>$_GET['emp_id'],'from'=>$_GET['from'],'to'=>$_GET['to'],'page'=>$page));
                            }
                            
                        }
                    }else{
                        if(isset($_GET['emp_id'])){
                            $page = 0;
                            if(isset($_GET['page']) && $_GET['page']!=''){
                            $page=$_GET['page'];
                            }
                            Session::put('filter-timesheet', array('emp_id'=>$_GET['emp_id'],'from'=>$_GET['from'],'to'=>$_GET['to'],'page'=>$page));
                        }
                    }

                    $checkRedireaction = false;
                    if (strpos(url()->previous(),'approvetimeedit') !== false || strpos($_SERVER['REQUEST_URI'],'approvetime') !== false) {
                        $checkRedireaction = true;
                    } 

                    if($checkRedireaction && !empty(Session::get('filter-timesheet'))){
                        $emp_id = Session::get('filter-timesheet')['emp_id'];
                        $from = Session::get('filter-timesheet')['from'];
                        $to = Session::get('filter-timesheet')['to'];
                        $page = Session::get('filter-timesheet')['page'];
                        $returnUrl =  url('/')."/superadmintimesheetfilter?emp_id=".$emp_id."&from=".$from."&to=".$to."&page=".$page;
                    
                    ?>
                    <script>
                        window.location.href = "<?php echo $returnUrl; ?>";
                    </script>
                    <?php } 
                    if(isset($_GET['reset'])){
                        Session::forget('filter-timesheet'); ?>
                        <script>
                            window.location.href = "<?php echo url('/')."/approvetime"; ?>";
                        </script>
                    <?php } ?>
                
                    <form action="{{ route('superadmintimesheetfilter') }}" method="GET">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-sm-3 col-12">
                                    <div class="form-group">
                                        <label>Developer Name</label>
                                        <select class="form-control select" name="emp_id" id="emp_id">
                                            <option value="" selected>Select Developer</option>
                                            <?php foreach($userData as $data){ ?>
                                                <option value="<?php echo $data->id; ?>" 
                                                    <?php if(isset(Session::get('filter-timesheet')['emp_id'])){
                                                        if($data->id == Session::get('filter-timesheet')['emp_id']){
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
                                        <label>From</label>
                                        <input type="text" required id="from" class="form-control" name="from" 
                                            value="<?php 
                                                if(isset(Session::get('filter-timesheet')['from']) && (Session::get('filter-timesheet')['from']) != ''){ 
                                                    $date = strtotime(Session::get('filter-timesheet')['from']); 
                                                    echo date('m/d/Y', $date);
                                                }else{
                                                    $cureent_date = date('Y-m-d'); 
                                                    $date = strtotime($cureent_date.'-1 month'); 
                                                    echo date('m/d/Y', $date);
                                                }
                                            ?>"
                                        >
                                    </div>
                                </div>
                                <div class="col-sm-3 col-12">
                                    <div class="form-group">
                                        <label>To</label>
                                        <input type="text" required id="to" class="form-control" name="to"
                                            value="<?php
                                                if(isset(Session::get('filter-timesheet')['to']) && (Session::get('filter-timesheet')['to']) != ''){
                                                    $date = strtotime(Session::get('filter-timesheet')['to']); 
                                                    echo date('m/d/Y', $date);
                                                }else{
                                                    echo date('m/d/Y');
                                                }
                                            ?>"
                                        >
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
                            </div>
                        </div>
                    </form>
                <div class="col-md-12">
                    <div class="card ctm-border-radius shadow-sm grow">
                        <div class="card-header">
                            <div class="timesheet_export">
                                <h4 class="card-title mb-0">All Time log List</h4>
                                <input id="export" type="submit" class="multiple_btn" value="Export">
                            </div>
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
                                            <?php $i=0; ?>
                                                @foreach ($timesheetData as $timesheet)
                                                    <tr>
                                                        <td><input type="checkbox" name="checkbox" value="{{ $timesheet->id }}"></td>
                                                        <td>{{ ++$i }}</td>
                                                        <td>{{ $timesheet->project_name }}</td>
                                                        <td>{{ $timesheet->name }}</td>
                                                        <td>{{ $timesheet->hours }}h {{ $timesheet->minutes }}m</td>
                                                        <td><?php echo date("d/m/Y",strtotime($timesheet->select_date)); ?></td>
                                                        <td class="description_tool">
                                                            <div class="tooltip"><?php  echo strip_tags(trim(substr($timesheet->description,0,15))); ?>
                                                            <span class="tooltiptext"><?php echo strip_tags($timesheet->description); ?></span>
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
                                            <?php if($i==0) { ?>
                                            </table>
                                                <p class="time_log_list_p" >No record found</p>
                                            <?php } ?>
                                            {!! $timesheetData->links() !!}
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
</script>
<script>
    $(document).ready(function() {
        $("#from"). prop("readonly", true); 
        $("#to"). prop("readonly", true); 
    });
    $(document).ready(function() {
        $('#allchk').click(function() {
            $('input[type="checkbox"]').prop('checked', this.checked);
        })
    });
</script>
<script>
    $(document).ready(function(){
        $('#export').on('click', function() {
            if (confirm("Are you want to "+this.value) == true) {
                let selectedIds =[];
                $("input:checkbox[name=checkbox]:checked").each(function(){
                    selectedIds.push($(this).val());
                });
                if(selectedIds.length === 0){
                    confirm("The checked value can not be blank.");
                    return;
                }
                
                var emp_id = document.getElementById('emp_id').value;
                var to = document.getElementById('to').value;
                var from = document.getElementById('from').value;

                $.ajax({
                    url: "{{ route('approvetimemassexport') }}",
                    type: "POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "selectedIds":selectedIds,
                        "emp_id":emp_id,
                        "to":to,
                        "from":from,
                        "status" : this.value
                    },
                    dataType: "html",
                    success: function (data) {
                        /*
                        * Make CSV downloadable
                        */
                        var downloadLink = document.createElement("a");
                        var fileData = ['\ufeff'+data];

                        var blobObject = new Blob(fileData,{
                            type: "text/csv;charset=utf-8;"
                        });

                        var url = URL.createObjectURL(blobObject);
                        downloadLink.href = url;
                        downloadLink.download = "timesheet.csv";

                        /*
                        * Actually download CSV
                        */
                        document.body.appendChild(downloadLink);
                        downloadLink.click();
                        document.body.removeChild(downloadLink);
                    }
                });
            }
        });
    });
</script>
<script>
    $(document).ready(function(){
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
<script>
    $(document).ready(function(){
        $("#emp_id").select2();
    });
</script>
@endsection