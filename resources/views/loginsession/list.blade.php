@extends('layouts.layout')

@section('content')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<style>
    .leave_list {
        text-align: center;
        padding: 10px;
        border-radius: 4px;
        color: #000;
        margin-top: 15px;
        text-transform: capitalize;
    }
    input[type=date], input[type=datetime-local], input[type=email], input[type=number], input[type=password], input[type=search], input[type=tel], input[type=text], input[type=time], input[type=url], textarea, select {
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
        -webkit-box-shadow: none;
        box-shadow: none;
        outline: none;
        width: 100%;
        padding: 0px;
    }
    td.green {
        color: green !important;
    }
    td.red {
        color: #f00;
    }
    td.orange {
        color: #f6822d;
    }
</style>

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="col-md-12">
                    <div class="card ctm-border-radius shadow-sm grow">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Search Employee Login Time</h4>
                            <form action="{{ route('loginsessionsearch') }}" method="GET">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="col-sm-3 col-12">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <select class="form-control select" name="emp_id" id="emp_id">
                                                    <option value="" selected>Select Developer</option>
                                                    <?php foreach($userData as $data){ ?>
                                                        <option value="<?php echo $data->id; ?>" 
                                                            <?php if(isset($_GET['emp_id'])){
                                                                if($data->id == $_GET['emp_id']){
                                                                    echo "selected";
                                                                }
                                                            }?>
                                                        ><?php echo $data->name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-1 col-12">
                                            <div class="form-group">
                                                <label>Day</label>
                                                <select id="day" name="day">
                                                    <option value="">Day</option>
                                                <?php
                                                for ($i_day = 1; $i_day <= 31; $i_day++) { 
                                                    if(isset($_GET['day'])){
                                                        $selected = $_GET['day'] == $i_day ? ' selected' : '';
                                                        echo '<option value="'.str_pad($i_day,2,'0', STR_PAD_LEFT).'"'.$selected.'>'. str_pad($i_day,2,'0', STR_PAD_LEFT).'</option>'."\n";
                                                    }else{
                                                        $selected_day = date('d'); //current day
                                                        $selected = $selected_day == $i_day ? ' selected' : '';
                                                        echo '<option value="'.str_pad($i_day,2,'0', STR_PAD_LEFT).'"'.$selected.'>'. str_pad($i_day,2,'0', STR_PAD_LEFT).'</option>'."\n";
                                                    }
                                                }
                                                ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-12">
                                            <div class="form-group">
                                                <label>Month</label>
                                                <select id="month" name="month">
                                                    <option value="">Select Month</option>
                                                    <?php
                                                    for ($i_month = 1; $i_month <= 12; $i_month++) { 
                                                        if(isset($_GET['month'])){
                                                            $selected = $_GET['month'] == $i_month ? ' selected' : '';
                                                            echo '<option value="'.str_pad($i_month,2,'0', STR_PAD_LEFT).'"'.$selected.'>'. date('F', mktime(0,0,0,$i_month)).'</option>'."\n";
                                                        }else{
                                                            $selected_month = date('m'); //current month
                                                            $selected = $selected_month == $i_month ? ' selected' : '';
                                                            echo '<option value="'.str_pad($i_month,2,'0', STR_PAD_LEFT).'"'.$selected.'>'. date('F', mktime(0,0,0,$i_month)).'</option>'."\n";
                                                        }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 col-12">
                                            <div class="form-group">
                                                <label>Year</label>
                                                <select id="year" name="year">
                                                    <option value="">Select Year</option>
                                                    <?php $year_end = date('Y'); // current Year
                                                    for ($i_year = date("Y"); $i_year >= date("Y")-2; $i_year--) {
                                                        if(isset($_GET['year'])){
                                                            $selected = $_GET['year'] == $i_year ? ' selected' : '';
                                                            echo '<option value="'.$i_year.'"'.$selected.'>'.$i_year.'</option>'."\n";
                                                        }else{
                                                            $selected = $year_end == $i_year ? ' selected' : '';
                                                            echo '<option value="'.$i_year.'"'.$selected.'>'.$i_year.'</option>'."\n";
                                                        }
                                                    } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 col-12">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Submit</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-12">
                            <div class="card ctm-border-radius shadow-sm grow">
                                <div class="card-header">
                                    <div class="timesheet_export">
                                        <h4 class="card-title mb-0">Employee log List</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="employee-office-table">
                                        <div class="table-responsive">
                                            <table class="table custom-table mb-0">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Name</th>
                                                    <th>Work Hours</th>
                                                    <th>Location</th>
                                                    <th>Email</th>
                                                    <th>Mobile No</th>
                                                    <th>Date Time</th>
                                                    <th>Action</th>
                                                </tr>
                                                <?php if (count($logindata)){
                                                $i = 0; ?>
                                                @foreach ($logindata as $login)
                                                <tr>
                                                    <td>{{ ++$i }}</td>
                                                    <td <?php if($login->work_hours <= 7){ ?>
                                                            class="red"
                                                        <?php } elseif (($login->work_hours > 7) && ($login->work_hours < 8)) { ?>
                                                            class="orange"
                                                        <?php } elseif ($login->work_hours >= 8) { ?>
                                                            class="green"
                                                        <?php } ?> >
                                                        {{ $login->name }}
                                                    </td>
                                                    <td>{{ $login->work_hours }}</td>
                                                    <td>{{ $login->location }}</td>
                                                    <td>{{ $login->email }}</td>
                                                    <td>{{ $login->mobile_number }}</td>
                                                    <td>{{ date('d-m-Y h:i:s A', strtotime($login->created_at)) }}</td>
                                                    <td>
                                                        <a href="{{ url('loginsessionedit/'.$login->id.'/edit') }}">
                                                            <i style='font-size:24px' class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                        </a>
                                                    <td>
                                                </tr>
                                                @endforeach
                                            </table>
                                            {!! $logindata->links() !!}
                                            <?php } else { ?>
                                            </table>
                                                <p class="leave_list" >No record found</p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="card ctm-border-radius shadow-sm grow">
                                <div class="card-header">
                                    <div class="timesheet_export">
                                        <h4 class="card-title mb-0">Not Logged In Employee</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="employee-office-table">
                                        <div class="table-responsive">
                                            <table class="table custom-table mb-0">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Name</th>
                                                    <th>Employee Code</th>
                                                    <th>Mobile No</th>
                                                    <th>Email</th>
                                                </tr>
                                                <?php if (count($notLoginData)){
                                                 $i = 0; ?>
                                                @foreach ($notLoginData as $data)
                                                <tr>
                                                    <td>{{ ++$i }}</td>
                                                    <td>{{ $data->name }}</td>
                                                    <td>{{ $data->employee_code }}</td>
                                                    <td>{{ $data->mobile_number }}</td>
                                                    <td>{{ $data->email }}</td>
                                                </tr>
                                                @endforeach
                                            </table>
                                            <?php } else { ?>
                                            </table>
                                                <p class="leave_list" >No record found</p>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card ctm-border-radius shadow-sm grow">
                                <div class="card-header">
                                    <div class="timesheet_export">
                                        <h4 class="card-title mb-0">Today Employee Leave List</h4>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="employee-office-table">
                                        <div class="table-responsive">
                                            <table class="table custom-table mb-0">
                                                <tr>
                                                    <th>No</th>
                                                    <th>Name</th>
                                                    <th>Employee Code</th>
                                                    <th>Mobile No</th>
                                                    <th>Email</th>
                                                </tr>
                                                <?php if (count($empLeavedata)){
                                                  $i = 0; ?>
                                                @foreach ($empLeavedata as $leave)
                                                <tr>
                                                    <td>{{ ++$i }}</td>
                                                    <td>{{ $leave->name }}</td>
                                                    <td>{{ $leave->employee_code }}</td>
                                                    <td>{{ $leave->mobile_number }}</td>
                                                    <td>{{ $leave->email }}</td>
                                                </tr>
                                                @endforeach
                                            </table>
                                            <?php } else { ?>
                                            </table>
                                                <p class="leave_list" >No record found</p>
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
    </div>
</div>
<style type="text/css">
    .justify-between {
        width: 75px;
    }
</style>

<script>
    $(document).ready(function(){
        $("#emp_id").select2();
    });
</script>

@endsection