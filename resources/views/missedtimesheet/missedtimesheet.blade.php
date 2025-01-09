@extends('layouts.layout')

@section('content')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Missed TimeSheet</h4>
                </div>
                
                <div class="col-md-12">
                    <div class="card ctm-border-radius shadow-sm grow">
                        
                        <div class="card-body">
                        <div class="col-md-12">
                        <form action="{{ route('missedtimesheet_filter') }}" method="post" autocomplete="off">
                        @csrf	
                            <div class="row">
                                <div class="col-sm-3 col-12">
                                    <div class="form-group">
                                        <label>Date</label>
                                        <input required type="text" id="from" class="form-control" name="from" 
                                            value="<?=$requet_date?>" >
                                    </div>
                                    </div>
                                    <div class="col-sm-3 col-12" style="margin-top: 14px; margin-left: 0px;">
                                    <div class="form-group">
                                        <button type="submit" value="1" name="Submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Submit</button>
                                    
                                        <button type="button" id="rstbtn" value="1" name="Reset" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Reset</button>
                                    </div>
                               
                                </div>
</form>
                                            </div>
                            <div class="employee-office-table">
                                <div class="table-responsive">
                                    <table class="table custom-table mb-0">
                                        <tr>
                                            <th>No</th>
                                            <th>Emp Code</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <?php if(!isset($requet_date) && trim($requet_date)==""){?>
                                            <th>TimeSheet Filled Upto Date</th>
                                            <?php } ?>
                                        </tr>
                                        @foreach ($timesheetData as $i=>$tech)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $tech->employee_code }}</td>
                                            <td>{{ $tech->name }}</td>
                                            <td>{{ $tech->email}}</td>
                                            <?php if(!isset($requet_date) && trim($requet_date)==""){?>
                                            <td>{{ date('l d F Y',strtotime($tech->select_date))}}</td>
                                            <?php } ?>
                                        </tr>
                                        @endforeach
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
    $(function() {
        $( "#from" ).datepicker({ 
            changeYear: true,
            minDate: '-3Y'
        });
    });

    $('#rstbtn').on('click',function(){

        window.location.href="{{route('missedtimesheet')}}"
    })
</script>


@endsection