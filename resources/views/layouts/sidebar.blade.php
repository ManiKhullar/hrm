<div class=" col-xl-3 col-lg-4 col-md-12 theiaStickySidebar">
   <aside class="sidebar sidebar-user">
      <div class="row">
         <div class="col-md-12">
            <div class="card ctm-border-radius shadow-sm grow left_top_content">
               <div class="card-body py-4 lsidebar_brd">
                  <div class="row">
                     <div class="col-md-12 mr-auto text-left">
                        <div class="custom-search input-group">
                           <div class="custom-breadcrumb">
                              <?php 
                              $allRouteArray = array(
                                 'dashboard' => 'Dashboard',
                                 'employee_list' => 'Employee List',
                                 'project' => 'Project',
                                 'skill' => 'Skill',
                                 'manager' => 'Manager',
                                 'projectmanager' => 'Project Manager',
                                 'leave_list' => 'Leave management',
                                 'role' => 'Role',
                                 'timesheet' => 'Time Sheet',
                                 'menu_add' => 'Add Menu',
                                 'claim' => 'Claim',
                                 'category' => 'Category',
                                 'department' => 'User Management',
                                 'salary_slip' => 'Salary Slip',
                                 'employeeview' => 'My Profile',
                                 'salarylist' => 'Salary List',
                                 'salaryimport' => 'Salary Import',
                                 'salarygenerate' => 'Salary Generate',
                                 'salarycron' => 'Salary Cron',
                                 'empsalaryimport' => 'Employee Salary Import',
                                 'empimport' => 'Employee Import',
                                 'changepassword' => 'Change Password',
                                 'leave_add' => 'Leave Management',
                                 'menu_edit' => 'Menu Edit',
                                 'leave_add' => 'Leave Management',
                                 'employee_add' => 'Employee',
                                 'roleedit' => 'Role Edit',
                                 'approvetime' => 'Work Log',
                                 'claimedit' => 'Claim Edit',
                                 'pdfview' => 'Pdf View',
                                 'usercalandarview' => 'User Calandar View',
                                 'employee_view' =>'Employee View',
                                 'projectedit' => 'Project Edit',
                                 'projectfilter' => 'Project Filter',
                                 'managerdetails' => 'Manager Details',
                                 'managerdetailsfilter' => 'Manager Details Filter',
                                 'skilledit' => 'Skill Edit',
                                 'manageredit' =>'Manager Edit',
                                 'empleave_edit' => 'Employee Leave Edit',
                                 'empleave_update' => 'Employee Leave Update',
                                 'leave_edit' => 'Leave Edit',
                                 'roleedit' => 'Role Edit',
                                 'claimedit' => 'Claim Edit',
                                 'employeeview' => 'Employee View',
                                 'empstatuschange' => 'Employee Status Change',
                                 'timesheetedit' => 'Time Sheet Edit',
                                 'timesheetview' => 'Time Sheet View',
                                 'approvetimefilter' => 'Approve Time Filter',
                                 'approvetimeedit' => 'Approve Time Edit',
                                 'approvetimesheet' => 'New Time Sheet',
                                 'approvetimesheetfilter' => 'New Approve Time Sheet Filter',
                                 'approvetimesheetmass' => 'New Approve Time Sheetmass',
                                 'salarylistfilter' => 'Salary List Filter',
                                 'holiday' => 'Holiday',
                                 'holidayedit' => 'Holiday Edit',
                                 'holidayview' => 'Holiday View',
                                 'employeefilter' => 'Employee Filter',
                                 'etemplate' => 'Email Template',
                                 'etemplatesave' => 'Template Save',
                                 'etemplateedit' => 'Template Edit',
                                 'etemplateupdate' => 'Template Update',
                                 'superadmintimesheetfilter' => 'Super Admin Timesheet Filter',
                                 'depskill'=> 'Skill Department',
                                 'depskillsave'=> 'Department Skill Save',
                                 'depskilledit'=> 'Department Skill Edit',
                                 'depskillupdate'=> 'Department Skill Update',
                                 'empskill'=> 'Employee Skill',
                                 'empskillsave'=> 'Employee Skill Save',
                                 'empskilledit'=> 'Employee Skill Edit',
                                 'empskillupdate'=> 'Employee Skill Update',
                                 'accessleavelist'=> 'Access Leave List',
                                 'empband'=> 'Employee Band',
                                 'empbandsave'=> 'Employee Band Save',
                                 'empbandedit'=> 'Employee Band Edit',
                                 'empbandupdate'=> 'Employee Band Update',
                                 'timesheetfilter'=> 'Time Sheet Filter',
                                 'cms'=> 'Cms',
                                 'cmssave'=> 'Cms Save',
                                 'cmsedit'=> 'Cms Edit',
                                 'cmsupdate'=> 'Cms Update',
                                 'cmsdelete'=> 'Cms Delete',
                                 'leavefilter'=> 'Leave Filter',
                                 'empattendance'=>'Employee Attendance',
                                 'clientmaster'=> 'Client Master',
                                 'clientmasterimport'=>'Client Master Import',
                                 'clientmasteradd'=> 'Clientmaster Add',
                                 'clientmastersave'=>'Clientmaster Save',
                                 'clientmasteredit'=> 'Clientmaster Edit',
                                 'clientmasterupdate'=>'Clientmaster Update',
                                 'clientmasterlist'=>'Clientmaster List',
                                 'clientmasterfilter'=>'Clientmaster Filter',
                                 'admintimesheetadd'=> 'Admin Time Sheet Add',
                                 'admintimesheetsave'=> 'Admin Time Sheet Save',
                                 'assignprojectfilter'=> 'Assignproject Filter',
                                 'accessleaveedit' => 'Access Leave Edit',
                                 'loginsessionlist' => 'Login Session List',
                                 'loginsessionedit' => 'Login Session Edit',
                                 'loginsessionupdate' => 'Login Session Update',
                                 'loginsessionsearch' => 'Login Session Search',
                                 'emploginhours' => 'Login Hours',
                                 'approveclaim' => 'Approve Claim',
                                 'claimfilter' => 'Filter Claim',
                                 'approveclaimedit' => 'Approve Claim Edit',
                                 'addpolicy' => 'Add Policy',
                                 'policysave' => 'Policy Save',
                                 'policyedit' => 'Policy Edit',
                                 'policyupdate' => 'Policy Update',
                                 'policydelete' => 'Policy Delete',
                                 'empassets' => 'Employee Assets',
                                 'team_lead' => 'Team Lead',
                                 'technologyupdate' => 'Technology Update',
                                 'technologyedit' => 'Technology Edit',
                                 'technologysave' => 'Technology Save',
                                 'technology' => 'Technology',
                                 'projectreport' => 'Project Report',
                                 'empreport' => 'Employee Report',
                                 'missedtimesheet' => 'Missed Timesheet',
                                 'missedtimesheet_filter' => 'Missed Timesheet',
                                 'notice' => 'Employee Notice',
                                 'noticeedit' => 'Employee Notice Edit',
                                 'noticeupdate' => 'Employee Notice Update',
                                 'freeDisk' => 'Free Space',
                                 'boostserver' => 'Boost Server',
                                 'bancheport' => 'Bench',
                                 'emp_leave_add' => 'Employee Leave',
                                 'wfh_add' => 'Work From Home',
                                 'wfh_edit' => 'Work From Home',
                                 'accesswfhlist' => 'Access Wfh List',
                                 'accesswfh_edit' => 'Access Wfh Edit',
                                 'wfhfilter' => 'Access Wfh Edit'
                              );
                              ?>
                              <h4 class="text-dark" style="text-transform: uppercase;">
                              <?php echo $allRouteArray[Route::currentRouteName()];?></h4>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <?php
      use Illuminate\Support\Facades\DB;
      use Illuminate\Support\Facades\Auth;
      $roleType = Auth::user()->role;
      $selectMenu = DB::select("select * from menus where status=1 or status=2");
      $selectRole = DB::select("select * from role where department='".$roleType."'");
      $accessRole = explode(',', $selectRole[0]->access);      
      $finalMenuData = array_filter($selectMenu, function ($data) use($accessRole) {
         return (in_array($data->id,$accessRole));
      });

      $allRoutes = array_column($finalMenuData, 'routes_name');
      if($roleType!=5){
         // echo Route::currentRouteName();
         // echo "<pre>";
         // prinr_r($allRoutes);
         if(!in_array(Route::currentRouteName(),$allRoutes)){
            ?><script> window.location.href = "{{ route('dashboard')}}";</script><?php
         }

      }
      
      $finalMenuDatas = array_filter($finalMenuData, function ($data) use($accessRole) {
         return (in_array($data->id,$accessRole) && ($data->status!=2));
      });
// echo "<pre>";
// echo Route::currentRouteName();
// print_r($allRoutes);
// echo $roleType;
// print_r($finalMenuDatas);
   
      ?>
      <div class="sidebar-wrapper d-lg-block d-md-none d-none">
         <div class="card ctm-border-radius shadow-sm grow border-none">
            <div class="card-body cardb_leftsidebar">
               <div class="row no-gutters">
               <div class="sidebar_profile_dv card">
                    <div class="sidebar_inner_dv">
                    <?php  
                     $userInfo = Auth::user();
                     $user_id = Auth::user()->id;
                     $image_profile = DB::select("select profile_pic from emp_accounts where user_id=".$user_id);
                     $emp_registrations = DB::select("select * from emp_registrations where user_id=".$user_id);
                     $profileImage = "";
                     if(isset($image_profile[0]) && $image_profile[0]->profile_pic!=""){
                           $profileImage = $image_profile[0]->profile_pic;
                           $filepath = Auth::user()->employee_code;
                     ?>                                    
                        <img src="{{ asset('doc_images'.$filepath.'/'.$profileImage) }}" >
                           
                           <?php }else{?>
                                 <img src="{{ asset('assets/img/profiles/logo.png') }}">
                                 <?php }?>
                     <!-- <img src="assets/img/profiles/logo.png" alt="img"> -->
                    </div>
                    <div class="profile_details_dv">
                    <h3><b><?php echo $userInfo->name;?></b></h3>
                    <h3><?php  echo $emp_registrations[0]->job_title; ?></h3>
                    <!-- <h3>FrontEnd</h3> -->
                    </div>

                  </div>
                  @foreach($finalMenuDatas as $menuData)
                  <div class="col-6 align-items-center shadow-none text-center">
                     <a href="{{route($menuData->routes_name)}}" class="{{ Route::currentRouteName() == $menuData->routes_name || Route::currentRouteName() == 'projectedit' ? 'text-white active' : 'text-dark'  }} p-4 ctm-border-right ctm-border-left"><span class="lnr {{$menuData->class_name}} pr-0 pb-lg-2 font-23"></span><span class>{{$menuData->name}}</span></a>
                  </div>
                  @endforeach
               </div>
            </div>
         </div>
      </div>
   </aside>
</div>

<style>
   .flex.justify-between.flex-1.sm\:hidden {
    display: none;
}
</style>
<script>
$(document).ready(function(){
   $('title').html($('.text-dark').html().trim());
});
</script>
