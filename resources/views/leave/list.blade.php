@extends('layouts.layout')
 
@section('content')

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.7.0/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.1.1/fullcalendar.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.7.0/moment.min.js"></script>


<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>
<div class="col-xl-9 col-lg-8 col-md-12">
   <div class="row">
      <div class="col-md-12 d-flex">
         <div class="card ctm-border-radius shadow-sm flex-fill">
            <div class="card-header leave_add_btn">
               <h4 class="card-title mb-0">
                Holiday Calender
               </h4>
               <a href="{{route('leave_add')}}" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">Apply Leave</a>

            </div>
            <div class="card-body">
            <div class="color-code" style="width:15%">
               <table class="table table-borderless" width="50%">
                <tbody>
                <tr>
                    <td><div class="cal-present"></td>
                    <td>Present</td>
                </tr>
                <tr>
                <td><div class="cal-absent"></td>
                    <td>Absent</td>
                </tr>
                <tr>
                    <td><div class="cal-weekend"></td>
                    <td>Weekend</td>
                </tr>
                <tr>
                <td><div class="cal-holiday"></td>
                    <td>Holiday</td>
                </tr>
                </tbody>
              </table>
            </div>
            <div class="container">
                <div id='calendars'></div>
                </div>
                <!-- /.modal -->
            </div>
         </div>
      </div>
   </div>
</div>
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" /> -->
<link rel="stylesheet" href="{{ asset('assets/css/fullcalendar.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>

<script>
   
    $(document).ready(function() {
        var calendar = $('#calendars').fullCalendar({
            editable:true,
            height: 650,
            width:400,
            header:{
                left:'prev,next today',
                center:'title',
                right:'month,agendaWeek,agendaDay'
            },
            events: "{{ route('calandar_view') }}",
            selectable:true,
            selectHelper:true,
            eventAfterRender: function (event, element, view) {
                console.log(event, element, view);
                element.append(event.title);
            },
            editable:true,
            eventRender: function(event, element) {
                var dataToFind = moment(event.start).format('YYYY-MM-DD');
                $("td[data-date='" + dataToFind + "']").addClass('activeDay');
            }
        });
    });
   
</script>
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

    .cal-present{
        background: green;
        height: 10px;
        width: 10px;
        border-radius: 20px;
    }

    .cal-absent{
        background: red;
        height: 10px;
        width: 10px;
        border-radius: 20px;
    }

    .cal-weekend{
        background: gray;
        height: 10px;
        width: 10px;
        border-radius: 20px;
    }

    .cal-holiday{
        background: yellow;
        height: 10px;
        width: 10px;
        border-radius: 20px;
    }
    
    .fc-bgevent {
        opacity: 1 !important;
        color:#000;
    }
</style>
@endsection
