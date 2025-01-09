@extends('layouts.layout')

@section('content')

<div class="col-xl-9 col-lg-8 col-md-12">
    <div class="row">
        <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow">
                <div class="card-header">
                    <h4 class="card-title mb-0">Salary Slip</h4>
                </div>
                <div class="card-body">
                    <a href="{{ url('salarylist') }}"  class="btn btn-theme button-1 text-white ctm-border-radius mt-4" style="float:right">Back</a>
                    <p><span>Name: </span><?php echo $empData[0]->name;?></p>
                    <p><span>Employee Code: </span><?php echo $empData[0]->employee_code;?></p>
                    <p><span>Email: </span><?php echo $empData[0]->email;?></p>
                    <p><span>Credit Salary: </span><?php echo $empData[0]->credit_salary;?></p>
                    <p><span>Casual Leave: </span><?php echo $total_leave['emp_casual_leaves'];?></p>
                    <p><span>Sick Leave: </span><?php echo $total_leave['emp_sick_leaves'];?></p>
                    <p><span>Leave Without Pay: </span><?php echo $total_leave['emp_lwp_leaves'];?></p>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
                    <script>
                        $(document).ready(function() {
                            var calendar = $('#calendars').fullCalendar({
                                editable:true,
                                header:{
                                    left:'prev,next today',
                                    center:'title',
                                    right:'month,agendaWeek,agendaDay'
                                },
                                events: <?php echo  $data ;?>,
                                selectable:true,
                                selectHelper:true,
                                editable:true,
                                eventAfterRender: function (event, element, view) {
                                    console.log(event, element, view);
                                    element.append(event.title);
                                    
                                },
                                eventRender: function(event, element) {
                                    var dataToFind = moment(event.start).format('YYYY-MM-DD');
                                    $("td[data-date='" + dataToFind + "']").addClass('activeDay');
                                }
                            });
                        });
                    
                    </script>
                    </head>
                    <body>

                    <div class="container">
                        <div id="calendars"></div>
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
    
    .fc-bgevent {
        opacity: 1 !important;
        color:#000;
    }
</style>

@endsection