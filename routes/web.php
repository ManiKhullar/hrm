<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\WorkFromHomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TimeSheetController;
use App\Http\Controllers\ApproveTimeController;
use App\Http\Controllers\ClaimController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\SalarySlipController;
use App\Http\Controllers\CalandarViewController;
use App\Http\Controllers\EmployeeImportController;
use App\Http\Controllers\HolidayController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\DepartmentSkillController;
use App\Http\Controllers\EmpSkillController;
use App\Http\Controllers\EmpBands;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ClientMasterImportController;
use App\Http\Controllers\AdminTimeSheetController;
use App\Http\Controllers\LoginSession;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\ApproveTimeSheetController;
use App\Http\Controllers\TeamLeadController;
use App\Http\Controllers\TechnologyController;
use App\Http\Controllers\ProjectReportController;
use App\Http\Controllers\MissedTimesheetController;
use App\Http\Controllers\NoticeEmp;
use App\Http\Controllers\FreeDisk;
use App\Http\Controllers\Performance;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
}); */

date_default_timezone_set('Asia/Kolkata'); //India time (GMT+5:30)

Route::get('/',[AuthController::class,'index'])->name('index');

Route::group(['middleware'=>'guest'],function(){
    Route::get('index',[AuthController::class,'index'])->name('index');
    Route::post('login',[AuthController::class,'login'])->name('login');
    Route::get('forget_password', [ForgotPasswordController::class, 'forget_password'])->name('forget_password');
 //   Route::get('register',[AuthController::class,'register_view'])->name('register');
  //  Route::post('register',[AuthController::class,'register'])->name('register');

   
    Route::post('forget-password', [ForgotPasswordController::class, 'ForgetPasswordStore'])->name('ForgetPasswordPost');
    Route::get('reset-password/{token}', [ForgotPasswordController::class, 'ResetPassword'])->name('ResetPasswordGet');
    Route::post('reset-password', [ForgotPasswordController::class, 'ResetPasswordStore'])->name('ResetPasswordPost');
});
Route::group(['middleware'=>'auth'],function(){
    Route::get('dashboard',[AuthController::class,'dashboard'])->name('dashboard');
    Route::post('empassets',[AuthController::class,'emp_assets'])->name('empassets');

    Route::get('project',[AttendanceController::class,'project_add'])->name('project');
    Route::post('projectsave',[AttendanceController::class,'project_save'])->name('projectsave');
    Route::get('projectedit/{edit_id}/edit',[AttendanceController::class,'project_edit'])->name('projectedit');
    Route::get('projectupdate/{id}/{status}',[AttendanceController::class,'project_update'])->name('projectupdate');
    Route::post('projectfilter',[AttendanceController::class,'project_filter'])->name('projectfilter');
    Route::get('projectdelete/{del_id}/delete',[AttendanceController::class,'project_delete'])->name('projectdelete');

    Route::get('skill',[AttendanceController::class,'skill_add'])->name('skill');
    Route::post('skillsave',[AttendanceController::class,'skill_save'])->name('skillsave');
    Route::get('skilledit/{edit_id}/edit',[AttendanceController::class,'skill_edit'])->name('skilledit');
    Route::put('skillupdate/{id}',[AttendanceController::class,'skill_update'])->name('skillupdate');
    Route::get('skilldelete/{del_id}/delete',[AttendanceController::class,'skill_delete'])->name('skilldelete');

    Route::get('manager',[AttendanceController::class,'manager_add'])->name('manager');
    Route::post('managersavedata',[AttendanceController::class,'manager_save'])->name('managersavedata');
    Route::get('manageredit/{edit_id}/edit',[AttendanceController::class,'manager_edit'])->name('manageredit');
    Route::put('managerupdate/{id}',[AttendanceController::class,'manager_update'])->name('managerupdate');
    Route::get('managerdelete/{del_id}/delete',[AttendanceController::class,'manager_delete'])->name('managerdelete');

    Route::get('projectmanager',[AttendanceController::class,'project_manager_add'])->name('projectmanager');
    Route::post('projectmanagersave',[AttendanceController::class,'project_manager_save'])->name('projectmanagersave');
    Route::get('projectmanageredit/{edit_id}/edit',[AttendanceController::class,'projectmanager_edit'])->name('projectmanageredit');
    Route::get('projectmanagerupdate/{id}/{status}',[AttendanceController::class,'projectmanager_update'])->name('projectmanagerupdate');
    Route::get('projectmanagerdelete/{del_id}/delete',[AttendanceController::class,'projectmanager_delete'])->name('projectmanagerdelete');
    Route::get('assignprojectfilter',[AttendanceController::class,'assignproject_filter'])->name('assignprojectfilter');

    Route::get('managerdetails',[AttendanceController::class,'manager_details'])->name('managerdetails');
    Route::get('managerdetailsfilter',[AttendanceController::class,'managerdetails_filter'])->name('managerdetailsfilter');

    Route::post('city_ajax',[EmployeeController::class,'city_name'])->name('employee');
    Route::get('employee_add',[EmployeeController::class,'employee_add'])->name('employee_add');
    Route::post('employee_save',[EmployeeController::class,'employee_save'])->name('employee_save');
    Route::get('employee_list',[EmployeeController::class,'employee_list'])->name('employee_list');
    Route::get('employee_delete/{id}/delete',[EmployeeController::class,'employee_delete'])->name('employee_delete');
    Route::get('employee_import',[EmployeeController::class,'employee_import'])->name('employee_import');
    Route::post('employee_import_save',[EmployeeController::class,'employee_import_save'])->name('employee_import_save');
    Route::get('employee_export',[EmployeeController::class,'employee_export'])->name('employee_export');
    Route::get('employee_view/{id}',[EmployeeController::class,'employee_view'])->name('employee_view');
    Route::post('employeeAddressUpdate',[EmployeeController::class,'employeeAddress_update'])->name('employeeAddressUpdate');
    Route::post('employee_register',[EmployeeController::class,'employeeRegister_update'])->name('employee_register');
    Route::post('employeePerAddressUpdate',[EmployeeController::class,'employeePerAddress_update'])->name('employeePerAddressUpdate');
    Route::post('employeeFamilyUpdate',[EmployeeController::class,'employeeFamily_Update'])->name('employeeFamilyUpdate');
    Route::post('employeeAccountDetailsUpdate',[EmployeeController::class,'employeeAccountDetails_Update'])->name('employeeAccountDetailsUpdate');
    Route::post('employeeCommunicationUpdate',[EmployeeController::class,'employeeCommunication_Update'])->name('employeeCommunicationUpdate');
    Route::post('employeeEduactionUpdate',[EmployeeController::class,'employeeEduaction_Update'])->name('employeeEduactionUpdate');
    Route::post('employeeAccountUpdate',[EmployeeController::class,'employeeAccount_Update'])->name('employeeAccountUpdate');
    Route::post('employeePreviousUpdate',[EmployeeController::class,'employeePrevious_Update'])->name('employeePreviousUpdate');
    Route::get('employeeview',[EmployeeController::class,'view_employee'])->name('employeeview');
    Route::get('changepassword',[EmployeeController::class,'change_password'])->name('changepassword');
    Route::post('changepasswordsave',[EmployeeController::class,'change_password_save'])->name('changepasswordsave');
    Route::post('employeefilter',[EmployeeController::class,'employee_filter'])->name('employeefilter');
    Route::post('employeepicupload',[EmployeeController::class,'profile_pic_upload'])->name('employeepicupload');
    Route::get('empstatuschange/{id}/{status}',[EmployeeController::class,'empstatus_change'])->name('empstatuschange');
    
    Route::get('loginsessionlist',[LoginSession::class,'loginsession_list'])->name('loginsessionlist');
    Route::get('loginsessionsearch',[LoginSession::class,'loginsession_search'])->name('loginsessionsearch');
    Route::get('loginsessionedit/{edit_id}/edit',[LoginSession::class,'loginsession_edit'])->name('loginsessionedit');
    Route::put('loginsessionupdate/{id}',[LoginSession::class,'loginsession_update'])->name('loginsessionupdate');
    Route::get('emploginhours',[LoginSession::class,'emplogin_hours'])->name('emploginhours');

    Route::get('leave_list',[LeaveController::class,'leave_list'])->name('leave_list');
    Route::get('leave_add',[LeaveController::class,'leave_add'])->name('leave_add');
    Route::get('emp_leave_add/{id}',[LeaveController::class,'emp_leave_add'])->name('emp_leave_add');
    Route::post('leave_save',[LeaveController::class,'leave_save'])->name('leave_save');
    Route::get('empleave_edit/{edit_id}',[LeaveController::class,'empleave_edit'])->name('empleave_edit');
    Route::put('empleave_update/{id}',[LeaveController::class,'empleave_update'])->name('empleave_update');
    Route::get('leave_edit/{edit_id}',[LeaveController::class,'leave_edit'])->name('leave_edit');
    Route::put('leave_update/{id}',[LeaveController::class,'leave_update'])->name('leave_update');
    Route::get('leave_count',[LeaveController::class,'leave_count'])->name('leave_count');
    Route::get('leave_check',[LeaveController::class,'leave_check'])->name('leave_check');
    Route::get('accessleavelist',[LeaveController::class,'accessleave_list'])->name('accessleavelist');
    Route::get('leavefilter',[LeaveController::class,'leavefilter'])->name('leavefilter');
    Route::get('accessleaveedit/{edit_id}',[LeaveController::class,'accessleave_edit'])->name('accessleaveedit');

    Route::get('role',[RoleController::class,'role_add'])->name('role');
    Route::post('rolesave',[RoleController::class,'role_save'])->name('rolesave');
    Route::get('roleedit/{edit_id}/edit',[RoleController::class,'role_edit'])->name('roleedit');
    Route::put('roleupdate/{id}',[RoleController::class,'role_update'])->name('roleupdate');
    Route::get('roledelete/{del_id}/delete',[RoleController::class,'role_delete'])->name('roledelete');
    Route::get('menu_add',[RoleController::class,'menu_add'])->name('menu_add');
    Route::post('menu_save',[RoleController::class,'menu_save'])->name('menu_save');
    Route::get('menu_edit/{id}',[RoleController::class,'menu_edit'])->name('menu_edit');
    Route::put('menu_update/{id}',[RoleController::class,'menu_update'])->name('menu_update');
    Route::get('menu_delete/{id}',[RoleController::class,'menu_delete'])->name('menu_delete');

    Route::get('timesheet',[TimeSheetController::class,'timesheet_add'])->name('timesheet');
    Route::post('timesheetsave',[TimeSheetController::class,'timesheet_save'])->name('timesheetsave');
    Route::get('timesheetedit/{edit_id}/edit',[TimeSheetController::class,'timesheet_edit'])->name('timesheetedit');
    Route::put('timesheetupdate/{id}',[TimeSheetController::class,'timesheet_update'])->name('timesheetupdate');
    Route::get('timesheetview/{view_id}/view',[TimeSheetController::class,'timesheet_view'])->name('timesheetview');
    Route::get('timesheetfilter',[TimeSheetController::class,'timesheet_filter'])->name('timesheetfilter');
    Route::get('admintimesheetadd',[AdminTimeSheetController::class,'admintimesheet_add'])->name('admintimesheetadd');
    Route::post('admintimesheetsave',[AdminTimeSheetController::class,'admintimesheet_save'])->name('admintimesheetsave');

    Route::get('approvetime',[ApproveTimeController::class,'approvetime_add'])->name('approvetime');
    Route::get('approvetimefilter',[ApproveTimeController::class,'approvetime_filter'])->name('approvetimefilter');
    Route::get('approvetimeedit/{edit_id}/edit',[ApproveTimeController::class,'approvetime_edit'])->name('approvetimeedit');
    Route::put('approvetimeupdate/{id}',[ApproveTimeController::class,'approvetime_update'])->name('approvetimeupdate');
    Route::post('approvetimemass',[ApproveTimeController::class,'approvetime_mass'])->name('approvetimemass');
    Route::post('approvetimemassexport',[ApproveTimeController::class,'approvetimemass_export'])->name('approvetimemassexport');
    Route::get('superadmintimesheetfilter',[ApproveTimeController::class,'superadmintimesheet_filter'])->name('superadmintimesheetfilter');

    Route::get('approvetimesheet',[ApproveTimeSheetController::class,'approvetime_sheet'])->name('approvetimesheet');
    Route::post('approvetimesheetmass',[ApproveTimeSheetController::class,'approvetimesheet_mass'])->name('approvetimesheetmass');
    Route::get('approvetimesheetfilter',[ApproveTimeSheetController::class,'approvetimesheet_filter'])->name('approvetimesheetfilter');

    Route::get('depskill',[DepartmentSkillController::class,'depskill_add'])->name('depskill');
    Route::post('depskillsave',[DepartmentSkillController::class,'depskill_save'])->name('depskillsave');
    Route::get('depskilledit/{edit_id}/edit',[DepartmentSkillController::class,'depskill_edit'])->name('depskilledit');
    Route::get('depskillupdate/{id}/{status}',[DepartmentSkillController::class,'depskill_update'])->name('depskillupdate');
    Route::get('depskillview/{view_id}/view',[DepartmentSkillController::class,'depskill_view'])->name('depskillview');

    Route::get('empskill',[EmpSkillController::class,'empskill_add'])->name('empskill');
    Route::post('empskillsave',[EmpSkillController::class,'empskill_save'])->name('empskillsave');
    Route::get('empskilledit/{edit_id}/edit',[EmpSkillController::class,'empskill_edit'])->name('empskilledit');
    Route::put('empskillupdate/{id}',[EmpSkillController::class,'empskill_update'])->name('empskillupdate');
    Route::get('empskilldelete/{delete_id}/delete',[EmpSkillController::class,'empskill_delete'])->name('empskilldelete');

    Route::get('cms',[CmsController::class,'cms_add'])->name('cms');
    Route::post('cmssave',[CmsController::class,'cms_save'])->name('cmssave');
    Route::get('cmsedit/{edit_id}/edit',[CmsController::class,'cms_edit'])->name('cmsedit');
    Route::put('cmsupdate/{id}',[CmsController::class,'cms_update'])->name('cmsupdate');
    Route::get('cmsdelete/{delete_id}/delete',[CmsController::class,'cms_delete'])->name('cmsdelete');

    Route::get('salary_slip',[SalarySlipController::class,'salary_slip'])->name('salary_slip');
    Route::get('salarylist',[SalarySlipController::class,'salary_list'])->name('salarylist');
    Route::get('salarylistfilter',[SalarySlipController::class,'salarylist_filter'])->name('salarylistfilter');
    Route::get('calandar_view',[SalarySlipController::class,'calandar_view'])->name('calandar_view');
    Route::get('user_calandar/{user_id}',[SalarySlipController::class,'user_calandar'])->name('user_calandar');
    Route::get('usercalandarview/{id}',[SalarySlipController::class,'user_calandar_view'])->name('usercalandarview');
    Route::post('exportsalaryCSV',[SalarySlipController::class,'exportsalaryCSV'])->name('exportsalaryCSV');
    Route::get('pdfview/{id}',[SalarySlipController::class,'pdfview'])->name('pdfview');
    Route::get('empattendance/{id}',[SalarySlipController::class,'empattendance'])->name('empattendance');
    Route::get('salaryimport',[SalarySlipController::class,'salary_import'])->name('salaryimport');
    Route::post('empsalaryimport',[SalarySlipController::class,'emp_salary_import'])->name('empsalaryimport');
    Route::get('salarygenerate',[SalarySlipController::class,'salary_generate'])->name('salarygenerate');
    Route::post('salarycron',[SalarySlipController::class,'salary_cron'])->name('salarycron');

    Route::get('claim',[ClaimController::class,'claim_add'])->name('claim');
    Route::post('claimsave',[ClaimController::class,'claim_save'])->name('claimsave');
    Route::get('claimedit/{edit_id}/edit',[ClaimController::class,'claim_edit'])->name('claimedit');
    Route::post('claimupdate/{id}',[ClaimController::class,'claim_update'])->name('claimupdate');
    Route::get('claimview/{view_id}/view',[ClaimController::class,'claim_view'])->name('claimview');
    Route::post('deleteimg',[ClaimController::class,'delete_img'])->name('deleteimg');
    Route::get('approveclaim',[ClaimController::class,'approve_claim'])->name('approveclaim');
    Route::get('claimfilter',[ClaimController::class,'claim_filter'])->name('claimfilter');
    Route::get('approveclaimedit/{edit_id}/edit',[ClaimController::class,'approveclaim_edit'])->name('approveclaimedit');
    Route::post('approveclaimupdate/{id}',[ClaimController::class,'approveclaim_update'])->name('approveclaimupdate');

    Route::get('holiday',[HolidayController::class,'holiday_add'])->name('holiday');
    Route::post('holidaysave',[HolidayController::class,'holiday_save'])->name('holidaysave');
    Route::get('holidayedit/{edit_id}/edit',[HolidayController::class,'holiday_edit'])->name('holidayedit');
    Route::put('holidayupdate/{id}',[HolidayController::class,'holiday_update'])->name('holidayupdate');

    Route::get('empband',[EmpBands::class,'empband_add'])->name('empband');
    Route::post('empbandsave',[EmpBands::class,'empband_save'])->name('empbandsave');
    Route::get('empbandedit/{edit_id}/edit',[EmpBands::class,'empband_edit'])->name('empbandedit');
    Route::put('empbandupdate/{id}',[EmpBands::class,'empband_update'])->name('empbandupdate');

    Route::get('etemplate',[EmailTemplateController::class,'etemplate_add'])->name('etemplate');
    Route::post('etemplatesave',[EmailTemplateController::class,'etemplate_save'])->name('etemplatesave');
    Route::get('etemplateedit/{edit_id}/edit',[EmailTemplateController::class,'etemplate_edit'])->name('etemplateedit');
    Route::put('etemplateupdate/{id}',[EmailTemplateController::class,'etemplate_update'])->name('etemplateupdate');

    Route::get('category',[CategoryController::class,'category_add'])->name('category');
    Route::post('categorysave',[CategoryController::class,'category_save'])->name('categorysave');
    Route::get('categoryupdate/{id}/{status}',[CategoryController::class,'category_update'])->name('categoryupdate');
    Route::get('categoryview/{view_id}/view',[CategoryController::class,'category_view'])->name('categoryview');

    Route::get('department',[DepartmentController::class,'department_add'])->name('department');
    Route::post('departmentsave',[DepartmentController::class,'department_save'])->name('departmentsave');
    Route::get('departmentupdate/{id}/{status}',[DepartmentController::class,'department_update'])->name('departmentupdate');
    Route::get('departmentview/{view_id}/view',[DepartmentController::class,'department_view'])->name('departmentview');
    
    Route::get('empimport',[EmployeeImportController::class,'empimport'])->name('empimport');
    Route::post('empimportdata',[EmployeeImportController::class,'empimport_data'])->name('empimportdata');

    Route::get('notice',[NoticeEmp::class,'notice_add'])->name('notice');
    Route::get('noticeedit/{id}/edit',[NoticeEmp::class,'notice_edit'])->name('noticeedit');
    Route::put('noticeupdate/{id}',[NoticeEmp::class,'notice_update'])->name('noticeupdate');
    
    Route::get('clientmaster',[ClientMasterImportController::class,'clientmaster'])->name('clientmaster');
    Route::post('clientmasterimport',[ClientMasterImportController::class,'clientmaster_import'])->name('clientmasterimport');
    Route::get('clientmasterlist',[ClientMasterImportController::class,'clientmaster_list'])->name('clientmasterlist');
    Route::get('clientmasteradd',[ClientMasterImportController::class,'clientmaster_add'])->name('clientmasteradd');
    Route::post('clientmastersave',[ClientMasterImportController::class,'clientmaster_save'])->name('clientmastersave');
    Route::get('clientmasteredit/{edit_id}/edit',[ClientMasterImportController::class,'clientmaster_edit'])->name('clientmasteredit');
    Route::put('clientmasterupdate/{id}',[ClientMasterImportController::class,'clientmaster_update'])->name('clientmasterupdate');
    Route::get('clientmasterfilter',[ClientMasterImportController::class,'clientmaster_filter'])->name('clientmasterfilter');
   
    Route::get('technology',[TechnologyController::class,'technology_add'])->name('technology');
    Route::post('technologysave',[TechnologyController::class,'technology_save'])->name('technologysave');
    Route::get('technologyedit/{edit_id}/edit',[TechnologyController::class,'technology_edit'])->name('technologyedit');
    Route::put('technologyupdate/{id}',[TechnologyController::class,'technology_update'])->name('technologyupdate');
 
    Route::get('addpolicy',[PolicyController::class,'policy_add'])->name('addpolicy');
    Route::post('policysave',[PolicyController::class,'policy_save'])->name('policysave');
    Route::get('policyedit',[PolicyController::class,'policy_edit'])->name('policyedit');
    Route::post('policyupdate',[PolicyController::class,'policy_update'])->name('policyupdate');

    Route::get('empreport',[ProjectReportController::class,'empListReport'])->name('empreport');
    Route::get('bancheport',[ProjectReportController::class,'banchEmpList'])->name('bancheport');
    Route::post('empreport',[ProjectReportController::class,'empListReport'])->name('empreport');
    Route::get('projectreport',[ProjectReportController::class,'projectreport'])->name('projectreport');
    Route::post('projectreport',[ProjectReportController::class,'projectreport'])->name('projectreport');

    Route::post('missedtimesheet_filter',[MissedTimesheetController::class,'missedtimesheet_filter'])->name('missedtimesheet_filter');
    Route::get('missedtimesheet',[MissedTimesheetController::class,'missedtimesheet'])->name('missedtimesheet');
    
    Route::get('team_lead',[TeamLeadController::class,'teamlead_add'])->name('team_lead');
    Route::post('add_teamlead',[TeamLeadController::class,'add_teamlead'])->name('add_teamlead');
    Route::get('delete_team_lead/{id}',[TeamLeadController::class,'delete_team_lead'])->name('delete_team_lead');
    
    Route::get('freeDisk',[FreeDisk::class,'free_disk'])->name('freeDisk');
    Route::post('boostserver',[FreeDisk::class,'boost_server'])->name('boostserver');
    
    Route::get('logout',[AuthController::class,'logout'])->name('logout');   

    Route::get('piplist',[Performance::class,'pip_list'])->name('piplist');
    Route::post('pipsave',[Performance::class,'project_save'])->name('pipsave');
    Route::get('pipedit/{edit_id}/edit',[Performance::class,'pip_edit'])->name('pipedit');
    Route::get('pipupdate/{id}/{status}',[Performance::class,'pip_update'])->name('pipupdate');
    Route::post('pipfilter',[Performance::class,'pip_filter'])->name('pipfilter');

    Route::get('wfh_add',[WorkFromHomeController::class,'wfh_add'])->name('wfh_add');
    Route::post('wfh_save',[WorkFromHomeController::class,'wfh_save'])->name('wfh_save');
    Route::get('wfh_edit/{edit_id}',[WorkFromHomeController::class,'wfh_edit'])->name('wfh_edit');
    Route::put('wfh_update/{id}',[WorkFromHomeController::class,'wfh_update'])->name('wfh_update');
    Route::get('accesswfhlist',[WorkFromHomeController::class,'accesswfh_list'])->name('accesswfhlist');
    Route::get('wfhfilter',[WorkFromHomeController::class,'wfhfilter'])->name('wfhfilter');
    Route::get('accesswfh_edit/{edit_id}',[WorkFromHomeController::class,'accesswfh_edit'])->name('accesswfh_edit');
    Route::put('update_wfh/{id}',[WorkFromHomeController::class,'update_wfh'])->name('update_wfh');
    

});
