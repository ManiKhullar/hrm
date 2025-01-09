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
</style>

<div class="col-xl-9 col-lg-8 col-md-12">
 <div class="row">
  
<div class="col-md-8 d-flex">
   <div class="card ctm-border-radius shadow-sm flex-fill">
    <div class="card-header">
     <h4 class="card-title mb-0">
      Apply Work From Home
  </h4>
</div>
<div class="card-body">
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    @if ($message = Session::get('success'))
    <div class="alert alert-success">
        <p>{{ $message }}</p>
    </div>
    @endif

    <form id="leave_data" method="post" action="{{  url('wfh_save') }}" autocomplete="off">
        @csrf
        <div class="row">
            <div class="col-sm-6">
            <div class="form-group">
                    <label>CC Email To</label>
                    <input type="text" class="form-control"  name="cc">
                    <span class="text-info">* Multiple email with comma (,) separated.</span>
                </div>
               
                    <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>
                        Project Manager
                        <span class="text-danger">*</span>
                    </label>
                    <?php
                    use Illuminate\Support\Facades\DB;
                    use Illuminate\Support\Facades\Auth;
                    $user_id = Auth::user()->id; 
                    if(!empty($manager)){ 
                    ?>
                    <p>{{$manager[0]->name}}</p>
                    <input type="text" required hidden class="form-control" name="project_manager" id="project_manager" tabindex="-1" aria-hidden="true" value="{{$manager[0]->id}}">
                    <?php } ?>
                </div>
            </div>
            <div class="col-sm-6">
                
            </div>
        </div>
        
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label>From</label>
                    <span class="text-danger">*</span>
                    <input type="text" readonly required name="start_date" id="start_date"  >
                    <p id="start_date_error" class="text-danger"></p>
                </div>
            </div>
            <div class="col-sm-6 leave-col">
                <div class="form-group">
                    <label>To</label>
                    <span class="text-danger">*</span>
                    <input type="text" readonly required name="end_date" id="end_date" >
                    <p id="end_date_error" class="text-danger"></p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group mb-0">
                    <label>Reason</label>
                    <span class="text-danger">*</span>
                    <textarea class="form-control" required name="message" id="message" rows="4"></textarea>
                    <p id="message_error" class="text-danger"></p>
                </div>
            </div>
        </div>
        <div class="text-center cancel_btn">
            <input type="submit" value="Apply" class="btn btn-theme button-1 text-white ctm-border-radius mt-4">
            <a href="{{ route('leave_list') }}" class="btn btn-danger text-white ctm-border-radius mt-4">Cancel</a>
        </div>
    </form>
    <div class="col-md-12">
     <div class="card ctm-border-radius shadow-sm grow">
      <div class="card-header">
       <h4 class="card-title mb-0">Applied  Work From Home List</h4>
   </div>
   <div class="card-body">
       <div class="employee-office-table">
        <div class="table-responsive">
         <table class="table custom-table mb-0">
             
          <tr>
           <th>No</th>
           <th>Employee Name</th>
           <th>Start Date</th>
           <th>End Date</th>
           <th>Wfh Days</th>
           <th>Status</th>
           <th width="280px">Action</th>
       </tr>
       
        <?php if (count($leave)){ 
            $id = 0; ?>
            @foreach($leave as $leaveData)
            <tr>
                <td>{{ ++$id }}</td>
                <td>{{$leaveData->name}}</td>
                
                
                <td><?php echo date('d/m/Y',strtotime($leaveData->start_date)); ?></td>
                <td><?php echo date('d/m/Y',strtotime($leaveData->end_date)); ?></td>
                <td>{{$leaveData->leave_count}}</td>
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
                    @if($leaveData->status == 0)
                        <a class="btn btn-primary" href="{{ url('wfh_edit/'.$leaveData->id) }}">Edit</a>
                    @endif
                </td>     
            </tr>
            @endforeach
        </table>
        <?php }else { ?>
        </table>
            <p class="leave_list" >No record found</p>
        <?php } ?>
        {!! $leave->links() !!}
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        

        $('#start_date').datepicker({
            changeMonth: true,
            changeYear:true,
            showButtonPanel: true,
            minDate: new Date(),
            onSelect: function(selected) {
                $("#end_date").datepicker("option","minDate", selected);
                $('#end_date').val($('#start_date').val());
            }
        });
            
        $( "#end_date" ).datepicker({
            minDate: new Date(),
        });
        
 
    });
</script>
<style>
/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
  -webkit-animation-name: fadeIn; /* Fade in the background */
  -webkit-animation-duration: 0.4s;
  animation-name: fadeIn;
  animation-duration: 0.4s
}

/* Modal Content */
.modal-content {
  position: fixed;
  bottom: 50%;
  background-color: #fefefe;
  width: 50%;
  left: 20%;
  -webkit-animation-name: slideIn;
  -webkit-animation-duration: 0.4s;
  animation-name: slideIn;
  animation-duration: 0.4s
}

/* The Close Button */
.close {
  color: white;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

.modal-header {
  padding: 2px 16px;
  background-color: white;
}

.modal-body {padding: 2px 16px;}

.modal-footer {
  padding: 2px 16px;
  background-color: white;
}

/* Add Animation */
@-webkit-keyframes slideIn {
  from {bottom: -300px; opacity: 0} 
  to {bottom: 0; opacity: 1}
}

@keyframes slideIn {
  from {bottom: -300px; opacity: 0}
  to {bottom: 0; opacity: 1}
}

@-webkit-keyframes fadeIn {
  from {opacity: 0} 
  to {opacity: 1}
}

@keyframes fadeIn {
  from {opacity: 0} 
  to {opacity: 1}
}
</style>
</head>
<body>

    <!-- Trigger/Open The Modal -->


    <!-- The Modal -->
    <div id="myModal" class="modal confirmation_popup">

      <!-- Modal content -->
      <div class="modal-content">
        <div class="modal-header">
          <span class="close">&times;</span>
          <h2>Confirmation</h2>
      </div>
      <div class="modal-body">
          <p id="model-data-text">Some text in the Modal Body</p>
      </div>
      <div class="modal-footer">
          <button id="confirmed">Confirmed</button>
          <button id="canceled">Cancel</button>
      </div>
  </div>

</div>
</div>

<script>

    var modal = document.getElementById("myModal");

// Get the button that opens the modal
    var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 

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
</script>
<style>
  table.ui-datepicker-calendar tbody tr td {
    padding: 6px;
}

table.ui-datepicker-calendar tbody {
    border: 1px solid #000;
    background: #fff;
}
</style>

@endsection
