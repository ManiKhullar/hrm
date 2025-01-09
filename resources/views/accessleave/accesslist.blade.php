@extends('layouts.layout')

@section('content')

<style>
    .leave_list {
        text-align: center;
        padding: 10px;
        border-radius: 4px;
        color: #000;
        margin-top: 15px;
        text-transform: capitalize;
    }

    .tooltip {
        position: relative;
        display: inline-block;
        opacity: inherit;
        z-index: 0;
    }

    .tooltip .tooltiptext {
        visibility: hidden;
        width: 500px;
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

 
}
</style>

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="card ctm-border-radius shadow-sm flex-fill">
        <div class="card-body">
            <div class="col-md-12">
                <div class="card ctm-border-radius shadow-sm grow">
                    <div class="card-header">
                        <h4 class="card-title mb-0">Applied Employee Leave List</h4>
                    </div>
                    <div class="card-body">

                        <?php
                        $fiilterData = array();
                        if(!empty(Session::get('filter-leave'))){
                            if(isset($_GET['emp_id'])){
                                if(Session::get('filter-leave')['emp_id'] != $_GET['emp_id'] || Session::get('filter-leave')['from'] != $_GET['from'] || Session::get('filter-leave')['to'] != $_GET['to'] || (isset($_GET['page']) && Session::get('filter-leave')['page'] != $_GET['page'])){
                                    $page = 0;
                                    if(isset($_GET['page']) && $_GET['page']!=''){
                                    $page=$_GET['page'];
                                    }
                                    Session::put('filter-leave', array('emp_id'=>$_GET['emp_id'],'from'=>$_GET['from'],'to'=>$_GET['to'],'page'=>$page));
                                }
                                
                            }
                        }else{
                            if(isset($_GET['emp_id'])){
                                $page = 0;
                                if(isset($_GET['page']) && $_GET['page']!=''){
                                $page=$_GET['page'];
                                }
                                Session::put('filter-leave', array('emp_id'=>$_GET['emp_id'],'from'=>$_GET['from'],'to'=>$_GET['to'],'page'=>$page));
                            }
                        }

                        $checkRedireaction = false;
                        if (strpos(url()->previous(),'accessleaveedit') !== false || strpos($_SERVER['REQUEST_URI'],'accessleavelist') !== false) {
                            $checkRedireaction = true;
                        } 

                        if($checkRedireaction && !empty(Session::get('filter-leave'))){
                            $emp_id = Session::get('filter-leave')['emp_id'];
                            $from = Session::get('filter-leave')['from'];
                            $to = Session::get('filter-leave')['to'];
                            $page = Session::get('filter-leave')['page'];
                            $returnUrl =  url('/')."/leavefilter?emp_id=".$emp_id."&from=".$from."&to=".$to."&page=".$page;
                        
                        ?>
                        <script>
                            window.location.href = "<?php echo $returnUrl; ?>";
                        </script>
                        <?php } 
                        if(isset($_GET['reset'])){
                            Session::forget('filter-leave'); ?>
                            <script>
                                window.location.href = "<?php echo url('/')."/accessleavelist"; ?>";
                            </script>
                        <?php } ?>

                        <form action="{{ route('leavefilter') }}" method="get">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-sm-3 col-12">
                                        <div class="form-group">
                                            <label>Name</label>
                                            <select class="form-control select" name="emp_id" id="emp_id">
                                                <option value="" selected>Select Developer</option>
                                                <?php foreach($userData as $data){ ?>
                                                    <option value="<?php echo $data->id; ?>" 
                                                        <?php if(isset(Session::get('filter-leave')['emp_id'])){
                                                            if($data->id == Session::get('filter-leave')['emp_id']){
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
                                            <input type="text" id="from" class="form-control" name="from" 
                                                value="<?php 
                                                    if(isset(Session::get('filter-leave')['from']) && (Session::get('filter-leave')['from']) != ''){ 
                                                        $date = strtotime(Session::get('filter-leave')['from']); 
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
                                            <input type="text" id="to" class="form-control" name="to"
                                                value="<?php
                                                    if(isset(Session::get('filter-leave')['to']) && (Session::get('filter-leave')['to']) != ''){
                                                        $date = strtotime(Session::get('filter-leave')['to']); 
                                                        echo date('m/d/Y', $date);
                                                    }else{
                                                        echo date('m/d/Y');
                                                    }
                                                ?>"
                                            >
                                        </div>
                                    </div>
                                    <div class="col-sm-1 col-12" style="margin-top: 14px; margin-left: 21px;">
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
                        <div class="employee-office-table">
                            <div class="table-responsive">
                                <table class="table custom-table mb-0">
                                    <tr>
                                        <th>No</th>
                                        <th>Employee Name</th>
                                        <th>Manager Name
                                        <th>Approved By</th></th>
                                        <th>Leave Type</th>
                                        <th>Days</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    
                                    <?php if (count($leaves)){ ?>
                                        <?php $i = 0; ?>
                                        @foreach($leaves as $leaveData)
                                        <?php //print_r($leaveData); exit;?>
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{$leaveData->name}}</td>
                                            <td>
												
												{{$managerArray[$leaveData->project_manager]}}</td>
                                            
                                            <td><?php 
                                           
                                            
                                            if(!is_null($leaveData->approved_by)){?>{{$authorisedUser[$leaveData->approved_by]}}  <?php }else { echo"-";}?></td>
                                            <td>
                                                <?php echo $total_leave_type[$leaveData->leave_type]; ?>
                                            </td>
                                            <td>{{$leaveData->leave_count}}</td>
                                            <td>
                                                <?php echo date('d/m/Y',strtotime($leaveData->start_date)); ?>
                                            </td>
                                            <td>
                                                <?php echo date('d/m/Y',strtotime($leaveData->end_date)); ?>
                                            </td>
                                            <td>
                                                <div class="tooltip"><?php echo substr($leaveData->message,0,10); ?>
                                                    <span class="tooltiptext">{{ $leaveData->message }}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if($leaveData->status == 1) { ?>
                                                    <button type="button" class="btn btn-success">Approved</button>
                                                <?php } elseif($leaveData->status == 2) { ?>
                                                    <button type="button" class="btn btn-danger">Reject</button>
                                                <?php } else { ?>
                                                    <button type="button" class="btn btn-danger">Pending</button>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if($leaveData->status == 1 || $leaveData->status == 0) { ?>
                                                    <a class="btn btn-primary" href="{{ url('accessleaveedit/'.$leaveData->id) }}">Edit</a>
                                                <?php } ?>
                                            </td>     
                                        </tr>
                                        @endforeach
                                    </table>
                                    {!! $leaves->links() !!}
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
    $(document).ready(function(){
        $("#emp_id").select2();
    });
</script>

@endsection
