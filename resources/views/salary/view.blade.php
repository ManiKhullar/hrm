@extends('layouts.layout')

@section('content')

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Salary Slip</h4>
                </div>

                <div class="col-md-12">
                    <div class="card ctm-border-radius shadow-sm grow">
                        <div class="card-body">
                            <div class="employee-office-table">
                                <div class="table-responsive">
                                    <table class="table custom-table mb-0">
                                        <tr>
                                            <th>No</th>
                                            <th>Employee Name</th>
                                            <th>Employee Id</th>
                                            <th>Month</th>
                                            <th>Year</th>
                                            <th>Action</th>
                                        </tr>
                                        <?php $i = 0; ?>
                                        @foreach ($userData as $value)
                                        <tr>
                                            <td>{{ ++$i }}</td>
                                            <td>{{ $value->name }}</td>
                                            <td>{{ $value->employee_code }}</td>
                                            <td>
                                                <?php echo date('F', strtotime($value->year.'-'.$value->month.'-01')); ?>
                                            </td>
                                            <td><?php echo $value->year; ?>
                                            </td>
                                            <td>
                                                <a target="_blank" href="{{ url('pdfview/'.$value->id) }}">
                                                    <i style='font-size:24px' class="fa fa-download" aria-hidden="true"></i>
                                                </a>
                                            </td>
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
</div>
<style type="text/css">
    .justify-between {
        width: 75px;
    }

    a.fc-day-grid-event.fc-h-event.fc-event.fc-start.fc-end.absent {
    background-color: red;
    }

    a.fc-day-grid-event.fc-h-event.fc-event.fc-start.fc-end.filledTimeSheet {
        background-color: green;
    }

    a.fc-day-grid-event.fc-h-event.fc-event.fc-start.fc-end.weekend {
        background-color: gray;
    }

    a.fc-day-grid-event.fc-h-event.fc-event.fc-start.fc-end.Company.Holidays {
        background-color: blue;
    }
    
    .fc-time{
        display:none;
    }
</style>

@endsection