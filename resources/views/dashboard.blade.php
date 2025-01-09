    @extends('layouts.layout')
@section('content')
<?php
$work_anversarys = array_merge($work_anversary,$work_anversary_next);
$dobs = array_merge($dob,$dob_next);
?>
<style>
   .today_wish_dv{
      background: #3c7d3b !important;
      color: #fff;
      font-weight: 700;
   }

   .modal-content.time_seet_popup {
      width: 65%;
      margin: auto;
      top: 25%;
      padding: 40px 30px;
      border-radius: 10px;
   }

   body {
      font-family: Arial, sans-serif;
      line-height: 1.5;
      background-color: #FFF;
      font-size: 16px;
      color: #2a2a2a;
   }

   marquee {
      
      background-color: #ffffff;
      padding: 10px;
   }
</style>
<?php
if(!empty($empAsset))
{
   if($interval >= 300){ ?>
      <script type="text/javascript">
         $(window).on('load', function() {
            // $('#myModal').modal('show');
            $('#myModal').modal({
               backdrop: 'static',
               keyboard: false
            })
         });
      </script>
   <?php }
}else{ ?>
   <script type="text/javascript">
      $(window).on('load', function() {
         // $('#myModal').modal('show');
         $('#myModal').modal({
            backdrop: 'static',
            keyboard: false
         })
      });
   </script>
<?php }?>
<form id="myform">
@csrf
   <div id="myModal" class="modal">

      <!-- Modal content -->
      <div class="modal-content time_seet_popup">
         <?php if(count($assets)) { ?>
         <div class="card-body">
            <div class="employee-office-table">
               <div class="table-responsive">
                  <h4 class="card-title mb-4">This Asset Assign To You</h4>
                  <table class="table custom-table mb-0 veiw_tab">
                     <tr>
                        <th>Asset Name</th>
                        <th>Model No.</th>
                        <th>Serial No.</th>
                        <th>Processor</th>
                        <th>Storage</th>
                        <th>RAM</th>
                        <th>Owned By</th>
                        <th>Assigned Date</th>
                     </tr>
                     @foreach ($assets as $asset)
                        <tr>
                           <td>{{ $asset['brand_name'] }}</td>
                           <td>{{ $asset['model_no'] }}</td>
                           <td>{{ $asset['asset_sr_no'] }}</td>
                           <td>{{ $asset['processor'] }}</td>
                           <td>{{ $asset['storage'] }}</td>
                           <td>{{ $asset['memory'] }}</td>
                           <td>{{ $asset['owned_by'] }}</td>
                           <td>{{ $asset['created_at'] }}</td>
                        </tr>
                     @endforeach
                  </table>
               </div>
            </div>
         </div>
         <?php } ?>
         <div class="row">
            <div class="col-sm-12 col-12">
                  <div class="form-group">
                     <label>Getting the serial number of a computer.</label>
                     <p>For windows command : "wmic bios get serialnumber" </p>
                        <p>For mac command : "ioreg -l | grep IOPlatformSerialNumber" </br>
                        For linux command : "sudo dmidecode -t system | grep Serial" </p>
                     <label>Comment *</label>
                     <textarea class="form-control" id="comment" name="comment"></textarea>
                  </div>
                  <div class="text-danger">

                  </div>
            </div>
         </div>
         <div class="text-center">
            <p id="button" type="submit" class="btn btn-theme button-1 text-white ctm-border-radius mt-4" >Submit</p>
         </div>
      </div>
   </div>
</form>
<div class="col-xl-9 col-lg-12  col-md-12">
   <div class="row">
      <!-- Offer content -->
      <?php if(count($empNotice)) { ?>
		  <div class="col-xl-12 col-lg-12  col-md-12 my-2">
    <div class="card shadow-sm flex-fill">
<div class="card-header align-items-center">
<h4 class="card-title mb-0 d-inline-block">Alert</h4>
</div>
<div class="employee-office-table">
<div class="table-responsive">
<marquee width="100%" direction="left" height="45px" behavior="scroll" scrollamount="3">
<p style="color:<?php echo $empNotice[0]->color ?>;">{{ $empNotice[0]->content }}</p>
</marquee>
</div>
</div>
</div>
</div>

      <?php } ?>

      <div class="col-xl-6 col-lg-12 d-flex">
         <div class="card shadow-sm flex-fill">
            <div class="card-header align-items-center">
               <h4 class="card-title mb-0 d-inline-block">Upcoming Birthday
               </h4>
               <a href="javascript:void(0)" class="d-inline-block float-right text-primary"><i class="lnr lnr-sync"></i></a>
            </div>
            <div class="employee-office-table">
               <div class="table-responsive dashboard_tab">
                  <table class="table custom-table mb-0">
                     <tbody>
                        <tr>
                           <th>No</th>
                           <th>User Name</th>
                           <th>Date Of Birthday</th>
                           <th>Remaining</th>
                        </tr>
                        <?php
                           $i=0;
                           foreach($dobs as $dob){
                           	$current_year = date("Y");
                           	$array = explode('-', $dob->dob);
                              if($array[1] == '01' && date('m') ==12){
                                 $current_year = ($current_year +1);
                              }
                           	$final_date = $current_year."-".$array[1]."-".$array[2];
                           	$earlier = new DateTime($final_date);
                           	$later = new DateTime(date('Y-m-d'));
                           	$pos_diff = $later->diff($earlier)->format("%r%a");
                           	 if($pos_diff < 0){
                              continue;
                           	 }
                           	 $i++;
                           	?>
                        <tr class="<?php if($pos_diff == 0){
                           echo 'today_wish_dv';
                           }?>">
                           <td><?php echo $i; ?></td>
                           <td><?php echo $dob->name; ?></td>
                           <td><?php
                              $date_month = date('m-d', strtotime($dob->dob));
                              $dob = date('Y')."-".$date_month;
                              echo date("jS M, D", strtotime($dob));
                              ?>
                           </td>
                           <td><?php
                              if($pos_diff == 0){
                              	echo "Today";
                                }else{
                              	echo $pos_diff = $later->diff($earlier)->format("%r%a")." days";
                                }
                               ?>
                           </td>
                        </tr>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
      <div class="col-xl-6 col-lg-12 d-flex">
         <div class="card shadow-sm flex-fill">
            <div class="card-header align-items-center">
               <h4 class="card-title mb-0 d-inline-block">Upcoming Work Anniversary</h4>
               <a href="javascript:void(0)" class="d-inline-block float-right text-primary"><i class="lnr lnr-sync"></i></a>
            </div>
            <div class="employee-office-table">
               <div class="table-responsive dashboard_tab">
                  <table class="table custom-table mb-0">
                     <tbody>
                        <tr>
                           <th>No</th>
                           <th>User Name</th>
                           <th>Date Of Joining</th>
                           <th>Remaining</th>
                        </tr>
                        <?php
                           $i=0;
                           foreach($work_anversarys as $work_anversary){

                           	$current_year = date("Y");
                           	$array = explode('-', $work_anversary->joining_date);
                              if($array[1] == '01' && date('m') ==12){
                                 $current_year = ($current_year +1);
                              }
                           	$final_date = $current_year."-".$array[1]."-".$array[2];
                           	$earlier = new DateTime($final_date);
                           	$later = new DateTime(date('Y-m-d'));

                           	 $pos_diff = $later->diff($earlier)->format("%r%a");
                           	 if($pos_diff < 0){
                              continue;
                           	 }
                           	 $i++;
                           	?>
                        <tr class="<?php if($pos_diff == 0){
                           echo 'today_wish_dv';
                           }?>">
                           <td><?php echo $i; ?></td>
                           <td><?php echo $work_anversary->name; ?></td>
                           <td><?php
                              $date_month = date('m-d', strtotime($work_anversary->joining_date));
                              $work_anversary_date = date('Y')."-".$date_month;
                              echo  date("jS M, D", strtotime($work_anversary_date));
                               ?></td>
                           </td>
                           <td><?php
                              if($pos_diff == 0){
                              	echo "Today";
                                }else{
                              	echo $pos_diff = $later->diff($earlier)->format("%r%a")." days";
                                } ?>
                           </td>
                        </tr>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
      </div>
      <div class="row">
      <?php if(count($wfh)) { ?>
      <div class="col-xl-6 col-lg-12 d-flex">
         <div class="card shadow-sm flex-fill">
            <div class="card-header align-items-center">
               <h4 class="card-title mb-0 d-inline-block">WFH Users
               </h4>
               
            </div>
            <div class="employee-office-table">
               <div class="table-responsive dashboard_tab">
                  <table class="table custom-table mb-0">
                     <tbody>
                        <tr>
                           <th>No</th>
                           <th>User Name</th>
                           <th>Employee Code</th>
                           
                           <!-- <th>Location</th> -->
                        </tr>
                        <?php
                           $i=0;
                           foreach($wfh as $home){
                           	$i++; ?>
                        <tr>
                           <td><?php echo $i; ?></td>
                           <td><?php echo $home->name; ?></td>
                           <td><?php echo $home->employee_code; ?></td>
                           </tr>
                        <?php } ?>
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
      <?php } ?>
      <?php if(count($onLeave)) { ?>
      <div class="col-xl-6 col-lg-12 d-flex">
         <div class="card shadow-sm flex-fill">
            <div class="card-header align-items-center">
               <h4 class="card-title mb-0 d-inline-block">Today Employee on Leave</h4>
               
            </div>
            <div class="employee-office-table">
               <div class="table-responsive dashboard_tab">
                  <table class="table custom-table mb-0">
                     <tbody>
                     <tr>
                        <th>No</th>
                        <th>User Name</th>
                        <th>Employee Code</th>
                        <th>Date</th>
                           
                        </tr>
                        <?php
                           $i=0;
                           foreach($onLeave as $leave){
                           	$i++; ?>
                        <tr>
                           <td><?php echo $i; ?></td>
                           <td><?php echo $leave->name; ?></td>
                           <td><?php echo $leave->employee_code; ?></td>
                           <td><?php echo $leave->start_date.'&nbsp;&nbsp;/&nbsp;&nbsp;'.$leave->end_date;?></td>
                        </tr>
                        <?php } ?>

                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
      <?php } ?>
     
      <!-----
      
      Start
      --->
      <?php if(count($assets)) { ?>
      <div class="col-xl-12 col-lg-12 d-flex">
<div class="card shadow-sm flex-fill">
<div class="card-header align-items-center">
<h4 class="card-title mb-0 d-inline-block">Assigned laptop</h4>
</div>
<div class="employee-office-table">
<div class="table-responsive dashboard_tab">
<table class="table custom-table mb-0">
<tbody>
<tr>
<th>Asset Name</th>
<th>Model No.</th>
<th>Serial No.</th>
<th>Processor</th>
<th>Storage</th>
<th>RAM</th>
<th>Owned By</th>
<th>Assigned Date</th>
</tr>
@foreach ($assets as $asset)
                     <tr>
                        <td>{{ $asset['brand_name'] }}</td>
                        <td>{{ $asset['model_no'] }}</td>
                        <td>{{ $asset['asset_sr_no'] }}</td>
                        <td>{{ $asset['processor'] }}</td>
                        <td>{{ $asset['storage'] }}</td>
                        <td>{{ $asset['memory'] }}</td>
                        <td>{{ $asset['owned_by'] }}</td>
                        <td>{{ $asset['created_at'] }}</td>
                     </tr>
                  @endforeach
</tbody>
</table>
</div>
</div>
</div>
</div>
<?php } ?>
      <!---- END --->
   </div>
</div>


<script>
$(document).ready(function(){
   $('#myform').on('click', '#button', function() {
      var comment = $("#comment").val();
      if(comment == ""){
         $(".text-danger").html("This field is required");
         return false;
      }

      $.ajax({
         url: "{{ route('empassets') }}",
         type: "POST",
         data: {
            "_token": "{{ csrf_token() }}",
            'comment':comment,
         },
         success: function (data) {
            window.location = "{{ route('dashboard') }}";
         }
      });
   });
});
</script>

@endsection
