<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmpLeave;
use DB;
use Illuminate\Support\Facades\Auth;
use DateTime;
use App\Http\Controllers\Crypt;
use App\Http\Helper\SendMail;
use App\Http\Controllers\Exception;

class LeaveController extends Controller
{
    public function leave_list(){
        $holiday = DB::select('select * from holidays');
        return view('leave/list',[
            'holiday'=> $holiday 
        ]);
    }

    public function leave_add()
    {
        $user_id = Auth::user()->id;
       // echo "select DISTINCT users.name, project_managers.manager_id as id from project_managers left join users on users.id=project_managers.manager_id where project_managers.status ='1' and project_managers.developer_id=".$user_id;
        $manager = DB::select("select DISTINCT users.name, project_managers.manager_id as id from project_managers left join users on users.id=project_managers.manager_id where project_managers.status = '1' and project_managers.developer_id=".$user_id);
        if(empty($manager)){
            $manager = DB::select("select  name, id from users where employee_code='BT11101002'");
        }
        
        $fromDate = date('Y-m-d', strtotime('first day of january this year'));
        $todayDate= date('Y-m-d');
       /* $leave = DB::table("emp_leaves")
            ->leftjoin("users", "users.id", '=', "emp_leaves.user_id")
            ->where('emp_leaves.user_id',$user_id)
            ->whereBetween('start_date', [$fromDate, $todayDate])
                    ->orWhereBetween('end_date', [$fromDate, $todayDate])
            ->paginate(10,array('emp_leaves.*','users.id AS users_id', 'users.name'));*/
            $leave = DB::table('emp_leaves')
            // ->where('status', 0)
            ->where('emp_leaves.user_id',$user_id)
            ->where(function ($query) use ($fromDate, $todayDate) {
                $query->whereBetween('start_date', [$fromDate, $todayDate])
                    ->orWhereBetween('end_date', [$fromDate, $todayDate]);
            })->paginate(10,array('emp_leaves.*','users.id AS users_id', 'users.name'));
       // print_r($leave);exit;
        $leaveCount = DB::select('select * from emp_registrations where user_id='.$user_id);
        $joining_date = "";
        $holiday = DB::select("select * from holidays where status='1' order by date ASC");
        $total_leave_type=array(
            // 'sick_leave'=>'Sick Leave',
            'casual_leave'=>'Casual Leave',
            //'special_leave'=>'Special Leave',
            'lwp'=>'Leave Without Pay'
        );
        if(isset($leaveCount[0]) && $leaveCount[0]->joining_date!=''){
            $joining_date = $leaveCount[0]->joining_date;
            $joinDates = date("Y-m-d", strtotime($joining_date));
            $date1 = date('Y-m-d');
            $date2 = $joinDates;
            $d1=new DateTime($date2); 
            $d2=new DateTime($date1);                                  
            $Months = $d2->diff($d1); 
            $monthscount = (($Months->y) * 12) + ($Months->m);
            $total_leave_type=array(
                // 'sick_leave'=>'Sick Leave',
                'casual_leave'=>'Casual Leave',
                //'special_leave'=>'Special Leave',
                'lwp'=>'Leave Without Pay'
            );
            //Leave Type
            $leaveType = array(
                // 'sick_leave'=>'Sick Leave',
                'casual_leave'=>'Casual Leave',
                //'special_leave'=>'Special Leave',
                'lwp'=>'Leave Without Pay'
            );
         
            $sickLeaveSum = DB::select("select SUM(leave_count) as leave_count from emp_leaves where leave_type='sick_leave' and status=1 and user_id=".$user_id);
            $sickLeaveSumUse = DB::select("select SUM(leave_count) as leave_count from emp_leaves where leave_type='sick_leave' and status=0  and user_id=".$user_id);

           // $casualLeaveSum = DB::select("select SUM(leave_count) as leave_count from emp_leaves where leave_type='casual_leave' and status=1 and user_id=".$user_id);
            $casualLeaveSum = $this->leaveCount($user_id,1);
          
           
           $casualLeaveSumUse = $this->leaveCount($user_id,0);
          // $casualLeaveSumUse = count($pendingCasualLeave);
           // $casualLeaveSumUse = DB::select("select SUM(leave_count) as leave_count from emp_leaves where leave_type='casual_leave' and status=0 and user_id=".$user_id);

            $special_leave = DB::select("select SUM(leave_count) as leave_count from emp_leaves where leave_type='special_leave' and status=1 and user_id=".$user_id);    
            $sickLeave = 0;
            if(isset($sickLeaveSum[0]->leave_count) && $sickLeaveSum[0]->leave_count != ""){
                $sickLeave = $sickLeaveSum[0]->leave_count;
            }
            $sickLeaveUse = 0;
            if(isset($sickLeaveSumUse[0]->leave_count) && $sickLeaveSumUse[0]->leave_count != ""){
                $sickLeaveUse = $sickLeaveSumUse[0]->leave_count;
            }
            $casualLeave = 0;
            if(isset($casualLeaveSum[0]) && $casualLeaveSum != ""){
                $casualLeave = $casualLeaveSum;
            }

            $casualLeaveUse = 0;
            if(isset($casualLeaveSumUse) && $casualLeaveSumUse != ""){
                $casualLeaveUse = $casualLeaveSumUse;
            }

            $leaveCountdata = [
                // 'slickLeave'=>array(
                //     'taken'=>$sickLeave,
                //     'remaining'=>($leaveCount[0]->sick_leave - $sickLeave),
                //     'remaining_use'=>$leaveCount[0]->sick_leave - ($sickLeaveUse+$sickLeave)
                // ),
                'casualLeave'=>array(
                    'taken'=>$casualLeave,
                    'remaining'=>($leaveCount[0]->casual_leave - $casualLeave),
                    'remaining_use'=>($leaveCount[0]->casual_leave - ($casualLeaveUse+$casualLeave)),
                )
            ];
            // if(($leaveCount[0]->sick_leave - ($sickLeaveUse+$sickLeave)) <= 0){
            //     $leaveType = array_filter($leaveType, function ($data) {
            //         return ($data != 'Sick Leave');
            //     });
            // }

            if(($leaveCount[0]->casual_leave - ($casualLeaveUse+$casualLeave)) <= 0){
                $leaveType = array_filter($leaveType, function ($data) {
                    return ($data != 'Casual Leave');
                });
            }

            // if(($leaveCount[0]->special_leave - 1) == 1){
            //     $leaveType = array_filter($leaveType, function ($data) {
            //         return ($data != 'Special Leave');
            //     });
            // }

            if($monthscount < 3){
                $leaveType = array(
                    'lwp' => 'Leave Without Pay'
                );
                $leaveCountdata = [
                   
                    'casualLeave' => array(
                        'taken' => 0,
                        'remaining' => 0,
                        'remaining_use' => 0
                    )
                ];
            }
        
        return view('leave/add',[
            'manager' => $manager,
            'leave' => $leave,
            'leaveType' => $leaveType,
            'leaveCount' => $leaveCount,
            'leavedata' => $leaveCountdata,
            'holiday' => $holiday,
            'user_id' => 0,
            'total_leave_type' => $total_leave_type
        ]);
    }else{
        return view('leave/add',[
            'manager' => [],
            'leave' => [],
            'leaveType' => [],
            'leaveCount' => 0,
            'holiday' => $holiday,
            'leavedata' => [
               
                'casualLeave' => array(
                    'taken' => 0,
                    'remaining' => 0
                )
                ],
            'total_leave_type' => $total_leave_type
        ]); 
    }
    }

    public function leave_check(Request $request){
        print_r($request);exit;
    }

    public function leave_save(Request $request)
    {
		if($request->user_id >0){
			$user_id = $request->user_id;
		}else{
        $user_id = Auth::user()->id;
		}
        $employee_name = Auth::user()->name;
        $request->validate([
            'start_date' => 'required',
            'end_date' =>  'required',
            'leave_type' =>  'required',
            'message' =>  'required',
        ]);
        $total_leave_type=array(
            'sick_leave'=>'Sick Leave',
            'casual_leave'=>'Casual Leave',
            //'special_leave'=>'Special Leave',
            'lwp'=>'Leave Without Pay'
        );
        $emailTemplate = DB::select("SELECT * FROM email_templates WHERE type = 'apply_leave_email_template' AND status = '1'");
        $startdate = date("Y-m-d", strtotime($request->start_date)); 
        $endDate = date("Y-m-d", strtotime($request->end_date));
        $str_start_date = strtotime($startdate);
        $str_end_date = strtotime($endDate);
        $datediff = $str_end_date - $str_start_date;
        $weekEndCount = $this->getWeekEndCount($request->start_date,$request->end_date);
        $count = ($datediff / (60 * 60 * 24))+1;
        $publicHolidayCount = $this->getPublicHolidayCount($request->start_date,$request->end_date,$count);
        $effectiveLeave = $count - $weekEndCount - $publicHolidayCount;
        $existTimesheet = DB::select("SELECT * FROM timesheet WHERE user_id = $user_id AND status IN ('Pending','Approved','ReferBack') AND select_date >= '$startdate' AND select_date <= '$endDate'");
        if(!empty($existTimesheet)){
            return redirect()->route('leave_add')->with('error','You have filled the timesheet.');
        }
        
        if($request->half_day=='first_half' || $request->half_day=='second_half'){
          $count =.5;
        }

        $manager_data = DB::select("SELECT name,email FROM users WHERE id = $request->project_manager");
        $manager_name = $manager_data[0]->name;
        $to = $manager_data[0]->email;
        if($request->extra!='' && $request->extra > 0)
        {
            $start_date = $request->start_date;
            $day = (ceil($request->remaining) - 1);
            $end_date = date('Y-m-d', strtotime($start_date . ' +'.$day.' day'));
            EmpLeave::create([
                'user_id'=>$user_id,
                'start_date'=>date("Y-m-d", strtotime($request->start_date)),
                'end_date'=>$end_date,
                'partical_leave'=>$request->half_day,
                'leave_type'=>$request->leave_type,
                'project_manager'=>$request->project_manager,
                'leave_count'=>$request->remaining,
                'cc'=>$request->cc,
                'message'=>$request->message,
                'status'=>0
            ]);
            
            $emails = 'hr@bluethink.in,'.$request->cc;
            $cc = trim($emails,',');
            $leave_apply_content = "<td style='border: 1px solid #ccc;'>".$total_leave_type[$request->leave_type]."</td><td style='border: 1px solid #ccc;'>".date("Y-m-d", strtotime($start_date))."</td><td style='border: 1px solid #ccc;'>".date("Y-m-d", strtotime($end_date))."</td><td style='border: 1px solid #ccc;'>".$request->remaining."</td><td style='border: 1px solid #ccc;'>".$request->message."</td>";
            $employee_name_html = str_replace("{{employee_name}}",$employee_name,$emailTemplate[0]->content);
            $manager_name_html = str_replace("{{manager_name}}",$manager_name,$employee_name_html);
            $html = str_replace("{{leave_apply_content}}",$leave_apply_content,$manager_name_html);
            SendMail::sendMail($html, $emailTemplate[0]->subject, $to, 'noreply@mybluethink.in', $cc);

            EmpLeave::create([
                'user_id'=>$user_id,
                'start_date'=>$end_date,
                'end_date'=>date("Y-m-d", strtotime($request->end_date)),
                'partical_leave'=>$request->half_day,
                'leave_type'=>"lwp",
                'project_manager'=>$request->project_manager,
                'leave_count'=>$request->extra,
                'cc'=>$request->cc,
                'message'=>$request->message,
                'status'=>0
            ]);
            $emails = 'hr@bluethink.in,'.$request->cc;
            $cc = trim($emails,',');
            $leave_apply_content = "<td style='border: 1px solid #ccc;'>".$total_leave_type[$request->leave_type]."</td><td style='border: 1px solid #ccc;'>".date("Y-m-d", strtotime($end_date))."</td><td style='border: 1px solid #ccc;'>".date("Y-m-d", strtotime($request->end_date))."</td><td style='border: 1px solid #ccc;'>".$request->extra."</td><td style='border: 1px solid #ccc;'>".$request->message."</td>";
            $employee_name_html = str_replace("{{employee_name}}",$employee_name,$emailTemplate[0]->content);
            $manager_name_html = str_replace("{{manager_name}}",$manager_name,$employee_name_html);
            $html = str_replace("{{leave_apply_content}}",$leave_apply_content,$manager_name_html);
            SendMail::sendMail($html, $emailTemplate[0]->subject, $to, 'noreply@mybluethink.in', $cc);
            
        }else{
            EmpLeave::create([
                'user_id'=>$user_id,
                'start_date'=>date("Y-m-d", strtotime($request->start_date)),
                'end_date'=>date("Y-m-d", strtotime($request->end_date)),
                'partical_leave'=>$request->half_day,
                'leave_type'=>$request->leave_type,
                'project_manager'=>$request->project_manager,
                'leave_count'=>$effectiveLeave,
                'cc'=>$request->cc,
                'message'=>$request->message,
                'status'=>0
            ]);
            $emails = 'hr@bluethink.in,'.$request->cc;
            $cc = trim($emails,',');
            $leave_apply_content = "<td style='border: 1px solid #ccc;'>".$total_leave_type[$request->leave_type]."</td><td style='border: 1px solid #ccc;'>".date("Y-m-d", strtotime($request->start_date))."</td><td style='border: 1px solid #ccc;'>".date("Y-m-d", strtotime($request->end_date))."</td><td style='border: 1px solid #ccc;'>".$count."</td><td style='border: 1px solid #ccc;'>".$request->message."</td>";
            $employee_name_html = str_replace("{{employee_name}}",$employee_name,$emailTemplate[0]->content);
            $manager_name_html = str_replace("{{manager_name}}",$manager_name,$employee_name_html);
            $html = str_replace("{{leave_apply_content}}",$leave_apply_content,$manager_name_html);
            SendMail::sendMail($html, $emailTemplate[0]->subject, $to, 'noreply@mybluethink.in', $cc);
        }

        $result = array(
            'status'=>'success',
            'message'=>'Leave sucessfully applied'
        );
        return redirect()->route('leave_add')->with('success','Leave sucessfully applied.');
       // return json_encode($result);
    }

    public function empleave_edit($edit_id)
    {
        $manager = DB::select('select * from managers');
        $leave = DB::select('select * from emp_leaves where id='.$edit_id);
        return view('leave/edit',[
            'manager'=> $manager,
            'leave'=> $leave
        ]);
    }
 
    public function empleave_update(Request $request, $id)
    {
        $request->validate([
            'message' => 'required',
        ]);
        
        $leave = EmpLeave::where('id', $id)->firstOrFail();
        $leave->update([
            'message' => $request->message,
            'status' => 0
        ]);
        return redirect()->route('leave_add')->with('success','Leave Updated Successfully.');
    }

    public function leave_edit($edit_id)
    {
        $manager = DB::select('select * from managers');
        $leave = DB::select('select * from emp_leaves where id='.$edit_id);
        return view('leave/edit',[
            'manager'=> $manager,
            'leave'=> $leave
        ]);
    }

    public function leave_update(Request $request,$id)
    {
        $request->validate([
        'status' =>  'required',
        ]);
        $role = Auth::user()->role;
        $approvalId = Auth::user()->id;
        $manag_name = Auth::user()->where('id', $approvalId)->firstOrFail();
        $leave = EmpLeave::where('id', $id)->firstOrFail();
        $total_leave_type=array(
            'sick_leave'=>'Sick Leave',
            'casual_leave'=>'Casual Leave',
            //'special_leave'=>'Special Leave',
            'lwp'=>'Leave Without Pay'
        );
        $employee_data = DB::select("SELECT name,email FROM users WHERE id = $leave->user_id");
        $employee_name = $employee_data[0]->name;
        $employee_email = $employee_data[0]->email;
        $to = $employee_email;
        $leave_status=array(
            0 => 'Pending',
            1 => 'Approved',
            2 => 'Rejected'
        );
//echo $approvalId; exit;
        if($request->status == 1){
            $emailTemplate = DB::select("SELECT * FROM email_templates WHERE type = 'approved_leave_email_template' AND status = '1'");
        }elseif($request->status == 2){
            $emailTemplate = DB::select("SELECT * FROM email_templates WHERE type = 'leave_reject_email_template' AND status = '1'");
        }else{
            $emailTemplate = DB::select("SELECT * FROM email_templates WHERE type = 'apply_leave_email_template' AND status = '1'");
        }

        $leave_apply_content = "<td style='border: 1px solid #ccc;'>".$total_leave_type[$leave->leave_type]."</td><td style='border: 1px solid #ccc;'>".$leave->start_date."</td><td style='border: 1px solid #ccc;'>".$leave->end_date."</td><td style='border: 1px solid #ccc;'>".$leave->leave_count."</td><td style='border: 1px solid #ccc;'>".$request->message."</td><td style='border: 1px solid #ccc;'>".$leave_status[$request->status]."</td>";
        $employee_name_html = str_replace("{{employee_name}}",$employee_name,$emailTemplate[0]->content);
        if($request->status == 0){
            $manager_datas = DB::select("SELECT name,email FROM users WHERE id = $leave->project_manager");
            $manager_name = $manager_datas[0]->name;
            $employee_name_html = str_replace("{{manager_name}}",$manager_name,$employee_name_html);
        }
        $updateManager = str_replace("{{manager_name}}",$manag_name->name,$employee_name_html);
        $html = str_replace("{{leave_apply_content}}",$leave_apply_content,$updateManager);
        SendMail::sendMail($html, $emailTemplate[0]->subject, $to, 'noreply@mybluethink.in', $leave->cc);
        /*$leave->update([
            'approved_by' => $approvalId,
            'message' => $request->message,
            'status' => $request->status
        ]);*/
        EmpLeave::where('id', $id)
       ->update([
           'approved_by' => Auth::user()->id,
           'message' => $request->message,
            'status' => $request->status
        ]);
        if($role == 2){
            return redirect()->route('leave_add')->with('success','Leave Updated Successfully.');
        } elseif($role == 4 || $role == 5 || $role == 6 || $role == 3){
            return redirect()->route('accessleavelist')->with('success','Leave Updated Successfully.');
        }
    }

    public function leave_count()
    {
        //  echo \Hash::make('admin@12345');
        //  exit;
        // try {
        //     $currrentMonth = date('m');
        //     $currrentYear = date('Y');
        //     $lastDateOfMonth = cal_days_in_month(CAL_GREGORIAN, $currrentMonth, $currrentYear);
        //     $leaveCount = DB::select('select * from emp_registrations');
        //     foreach($leaveCount as $leaveData)
        //     {
        //         $leaveDataforEmp = DB::select("select * from emp_registrations where user_id=".$leaveData->user_id);
        //         if(date('m')==01 && date('d')==01){
        //             if($leaveDataforEmp[0]->casual_leave<=6){
        //                 $getCasualLeave = $leaveDataforEmp[0]->casual_leave;
        //                 $getSlickLeave = 0;
        //             }

        //             if($leaveDataforEmp[0]->casual_leave>6){
        //                 $getCasualLeave = 6;
        //                 $getSlickLeave = 0;
        //             }
                    
        //             $getSlickLeave = 0;
        //             $update = DB::table('emp_registrations')
        //             ->where('user_id', $leaveData->user_id)
        //             ->update(array('casual_leave' => $getCasualLeave, 'sick_leave' => $getSlickLeave));
        //         }

        //         if(date('d') == $lastDateOfMonth){
        //             $casualLeave = 1;
        //             $sickLeave = .5;
        //             $getCasualLeave = $leaveDataforEmp[0]->casual_leave + $casualLeave;
        //             $getSlickLeave = $leaveDataforEmp[0]->sick_leave + $sickLeave;
        //             $update = DB::table('emp_registrations')
        //                         ->where('user_id', $leaveData->user_id)
        //                         ->update(array('casual_leave' => $getCasualLeave, 'sick_leave' => $getSlickLeave));
        //         }

        //     }
        // }catch(Exception $e) {
        //     echo 'Message: ' .$e->getMessage();
        // }   
    }

    public function accessleave_list()
    {
        $managerData = DB::select("SELECT id,name FROM users WHERE role in (3,4,5)");
        $authorisedUser =  array();
        foreach($managerData as $managerDatas){
            $authorisedUser[$managerDatas->id]=$managerDatas->name;
        }
       // print_r($authorisedUser);exit;
        $user_id = Auth::user()->id;
        $role = Auth::user()->role;
        $total_leave_type=array(
            'sick_leave'=>'Sick Leave',
            'casual_leave'=>'Casual Leave',
            //'special_leave'=>'Special Leave',
            'lwp'=>'Leave Without Pay'
        );
        $userData =  DB::select("select users.id, users.name from users
            left join emp_registrations on users.id=emp_registrations.user_id
            where emp_registrations.status='1'");
        $managerArray = array();
        foreach($userData as $userDatas){
            $managerArray[$userDatas->id]=$userDatas->name;
        }
        $cDate = date("Y-m-d");
        $fromDate = date("Y-m-d", strtotime("$cDate -60 days"));
        

        if($role == 4)
        {
            $leaves = DB::table("emp_leaves")
                ->leftjoin("users", "users.id", '=', "emp_leaves.user_id")
                ->leftjoin("emp_registrations", "emp_registrations.user_id", '=', "emp_leaves.user_id")
                ->leftjoin("project_managers", "project_managers.developer_id", '=', "emp_leaves.user_id")
                ->where('project_managers.manager_id',$user_id)
                ->where('emp_registrations.status','1')
                ->whereDate('emp_leaves.end_date', '>=' , $fromDate)
                ->groupBy('emp_leaves.id')
                ->orderBy('emp_leaves.id', 'DESC')
                ->paginate(10,array('emp_leaves.id as id','emp_leaves.start_date','emp_leaves.end_date',
                'emp_leaves.status','emp_leaves.leave_type','emp_leaves.project_manager','emp_leaves.approved_by',
                'users.id as user_id', 'users.name','emp_leaves.message','emp_leaves.leave_count'));
            return view('accessleave.accesslist',[
                'userData' => $userData,
                'leaves' => $leaves,
                'managerArray' => $managerArray,
                'authorisedUser' => $authorisedUser,
                'total_leave_type' => $total_leave_type
                ])->with('i', (request()->input('page', 1) - 1) * 5);
        } 
        if($role == 3 || $role == 5 || $role == 6){
            $leaves = DB::table("emp_leaves")
                ->leftjoin("users", "users.id", '=', "emp_leaves.user_id")
                ->leftjoin("emp_registrations", "emp_registrations.user_id", '=', "emp_leaves.user_id")
                ->where('emp_registrations.status','1')
                ->whereDate('emp_leaves.end_date', '>=' , $fromDate)
                ->groupBy('emp_leaves.id')
                ->orderBy('emp_leaves.id', 'DESC')
                ->paginate(10,array('emp_leaves.id as id','emp_leaves.start_date','emp_leaves.end_date',
                'emp_leaves.status','emp_leaves.leave_type','emp_leaves.project_manager','emp_leaves.approved_by',
                'users.id as user_id', 'users.name','emp_leaves.message','emp_leaves.leave_count'));
            return view('accessleave.accesslist',[
                'userData' => $userData,
                'leaves' => $leaves,
                'managerArray' => $managerArray,
                'authorisedUser' => $authorisedUser,
                'total_leave_type' => $total_leave_type
                ])->with('i', (request()->input('page', 1) - 1) * 5);
        }
    }

    public function accessleave_edit($edit_id)
    {
        $manager = DB::select('select * from managers');
        $leave = DB::select('select * from emp_leaves where id='.$edit_id);
        return view('accessleave/editaccessleave',[
            'manager'=> $manager,
            'leave'=> $leave
        ]);
    }

    public function leavefilter(Request $request)
    {
        $managerData = DB::select("SELECT id,name FROM users WHERE role in (3,4,5)");
        $authorisedUser =  array();
        foreach($managerData as $managerDatas){
            $authorisedUser[$managerDatas->id]=$managerDatas->name;
        }
        $from = date("Y-m-d", strtotime($request->from));
        $to = date("Y-m-d", strtotime($request->to));
        $userData =  DB::select("select users.id, users.name from users
            left join emp_registrations on users.id=emp_registrations.user_id
            where emp_registrations.status='1'");
        $managerArray = array();
        foreach($userData as $userDatas){
            $managerArray[$userDatas->id]=$userDatas->name;
        }
        $user_id = Auth::user()->id;
        $role = Auth::user()->role;
        $total_leave_type=array(
            'sick_leave'=>'Sick Leave',
            'casual_leave'=>'Casual Leave',
            //'special_leave'=>'Special Leave',
            'lwp'=>'Leave Without Pay'
        );

        if($role == 4)
        {
            if(!empty($request->emp_id))
            {
                $leaves = DB::table("emp_leaves")
                    ->leftjoin("users", "users.id", '=', "emp_leaves.user_id")
                    ->leftjoin("emp_registrations", "emp_registrations.user_id", '=', "emp_leaves.user_id")
                    ->leftjoin("project_managers", "project_managers.developer_id", '=', "emp_leaves.user_id")
                    ->where('emp_registrations.status','1')
                    ->where('project_managers.manager_id',$user_id)
                    ->Where('users.id', $request->emp_id)
                    ->WhereBetween('emp_leaves.start_date',[$from, $to])
                    ->distinct('emp_leaves.id')
                    ->orderBy('emp_leaves.id', 'DESC')
                    ->paginate(10,array('emp_leaves.id as id','emp_leaves.start_date','emp_leaves.end_date',
                    'emp_leaves.status','emp_leaves.leave_type','emp_leaves.message','emp_leaves.project_manager',
                    'users.id as user_id', 'users.name','emp_leaves.leave_count','emp_leaves.approved_by'));
            } else {
                $leaves = DB::table("emp_leaves")
                    ->leftjoin("users", "users.id", '=', "emp_leaves.user_id")
                    ->leftjoin("emp_registrations", "emp_registrations.user_id", '=', "emp_leaves.user_id")
                    ->leftjoin("project_managers", "project_managers.developer_id", '=', "emp_leaves.user_id")
                    ->where('emp_registrations.status','1')
                    ->where('project_managers.manager_id',$user_id)
                    ->WhereBetween('emp_leaves.start_date',[$from, $to])
                    ->distinct('emp_leaves.id')
                    ->orderBy('emp_leaves.id', 'DESC')
                    ->paginate(10,array('emp_leaves.id as id','emp_leaves.start_date','emp_leaves.end_date',
                    'emp_leaves.status','emp_leaves.leave_type','emp_leaves.message','emp_leaves.project_manager',
                    'users.id as user_id', 'users.name','emp_leaves.leave_count','emp_leaves.approved_by'));
            }
            if(!empty($request->emp_id)){
                $leaves->appends(['emp_id'=>$request->emp_id]);
            }

            if(!empty($from) && !empty($to)){
                $leaves->appends(['from'=>$request->from]);
                $leaves->appends(['to'=>$request->to]);
            }
            return view('accessleave.accesslist',[
                'userData' => $userData,
                'leaves'=> $leaves,
                'managerArray' => $managerArray,
                'authorisedUser' => $authorisedUser,
                'total_leave_type'=> $total_leave_type
                ]);
        } 
        if($role == 3 || $role == 5 || $role == 6)
        {
            if(!empty($request->emp_id))
            {
                $leaves = DB::table("emp_leaves")
                    ->leftjoin("users", "users.id", '=', "emp_leaves.user_id")
                    ->leftjoin("emp_registrations", "emp_registrations.user_id", '=', "emp_leaves.user_id")
                    ->leftjoin("project_managers", "project_managers.developer_id", '=', "emp_leaves.user_id")
                    ->where('emp_registrations.status','1')
                    ->Where('users.id', $request->emp_id)
                    ->WhereBetween('emp_leaves.start_date',[$from, $to])
                    ->distinct('emp_leaves.id')
                    ->orderBy('emp_leaves.id', 'DESC')
                    ->paginate(10,array('emp_leaves.id as id','emp_leaves.start_date','emp_leaves.end_date',
                    'emp_leaves.status','emp_leaves.leave_type','emp_leaves.message','emp_leaves.project_manager',
                    'users.id as user_id', 'users.name','emp_leaves.leave_count','emp_leaves.approved_by'));
            }else{
                $leaves = DB::table("emp_leaves")
                    ->leftjoin("users", "users.id", '=', "emp_leaves.user_id")
                    ->leftjoin("emp_registrations", "emp_registrations.user_id", '=', "emp_leaves.user_id")
                    ->leftjoin("project_managers", "project_managers.developer_id", '=', "emp_leaves.user_id")
                    ->where('emp_registrations.status','1')
                    ->WhereBetween('emp_leaves.start_date',[$from, $to])
                    ->distinct('emp_leaves.id')
                    ->orderBy('emp_leaves.id', 'DESC')
                    ->paginate(10,array('emp_leaves.id as id','emp_leaves.start_date','emp_leaves.end_date',
                    'emp_leaves.status','emp_leaves.leave_type','emp_leaves.message','emp_leaves.project_manager',
                    'users.id as user_id', 'users.name','emp_leaves.leave_count','emp_leaves.approved_by'));
            }

            if(!empty($request->emp_id)){
                $leaves->appends(['emp_id'=>$request->emp_id]);
            }

            if(!empty($from) && !empty($to)){
                $leaves->appends(['from'=>$request->from]);
                $leaves->appends(['to'=>$request->to]);
            }
               
            return view('accessleave.accesslist',[
                'userData' => $userData,
                'leaves'=> $leaves,
                'managerArray' => $managerArray,
                'authorisedUser' => $authorisedUser,
                'total_leave_type'=> $total_leave_type
                ]);
        }
    }

    public function getWeekEndCount($startDate,$endDate,$weekday=0,$array =[]){

        $startDate = new DateTime($startDate);
        $endDate = new DateTime($endDate);
        $weekEnd = array("Saturday","Sunday");
        $totalWeekend = 0;
        while($startDate <= $endDate ){
            // find the timestamp value of start date
            $timestamp = strtotime($startDate->format('d-m-Y'));
     
            // find out the day for timestamp and increase particular day
            if($weekday >0 ){
                if(!in_array( date('l', $timestamp),$weekEnd) && in_array( date('Y-m-d', $timestamp),$array)){
                    $totalWeekend++;
                }
            }else{
            if(in_array( date('l', $timestamp),$weekEnd)){
                $totalWeekend++;
            }
        }
            // increase startDate by 1
            $startDate->modify('+1 day');
        }
        return $totalWeekend;
        
    }

    public function getPublicHolidayCount($startDate,$endDate,$count){
        $startDate = date("Y-m-d",strtotime($startDate));
        $endDate = date("Y-m-d",strtotime($endDate)); 
        $holidays = DB::select("select date from holidays where `date` between '$startDate' and '$endDate' and status= '1'");
        print_r($holidays);
        echo count($holidays);
        $holidayArray = [];
        foreach($holidays as $holiday){
            $holidayArray[] = $holiday->date;
        }
        switch(count($holidays)){
            case 0:
                $startDate = 0;
                $endDate = 0;
                $publicHolidayOnWeekDay = 0;
            break;
            case 1:
                $weekEnd = array("Saturday","Sunday");
                $publicHolidayOnWeekDay = 0;
                if(!in_array( date('l', strtotime($holidays[0]->date)),$weekEnd)){
                    $publicHolidayOnWeekDay =  1;
                }
            break;
            default:
                $startDate = $holidays[0]->date;
                $enddate = $holidays[count($holidays)-1]->date;
                $publicHolidayOnWeekDay = $this->getWeekEndCount($startDate,$endDate,1,$holidayArray);
            break;
        }
            return $publicHolidayOnWeekDay;
        
    } 
    
    public function emp_leave_add($user_id){
		//$user_id = Auth::user()->id;
        $manager = DB::select("select DISTINCT users.name, project_managers.manager_id as id from project_managers left join users on users.id=project_managers.manager_id where project_managers.status ='1' and project_managers.developer_id=".$user_id);
        if(empty($manager)){
            $manager = DB::select("select  name, id from users where employee_code='BT11101002'");
        }
        $fromDate = date('Y-m-d', strtotime('first day of january this year'));
        $todayDate= date('Y-m-d');
        $leave = $leave = DB::table('emp_leaves')
        // ->where('status', 0)
        ->where('emp_leaves.user_id',$user_id)
        ->where(function ($query) use ($fromDate, $todayDate) {
            $query->whereBetween('start_date', [$fromDate, $todayDate])
                ->orWhereBetween('end_date', [$fromDate, $todayDate]);
        })
            ->paginate(10,array('emp_leaves.*','users.id AS users_id', 'users.name'));
        
        $leaveCount = DB::select('select * from emp_registrations where user_id='.$user_id);
        $joining_date = "";
        $holiday = DB::select("select * from holidays where status='1' order by date ASC");
        $total_leave_type=array(
            // 'sick_leave'=>'Sick Leave',
            'casual_leave'=>'Casual Leave',
            //'special_leave'=>'Special Leave',
            'lwp'=>'Leave Without Pay'
        );
        if(isset($leaveCount[0]) && $leaveCount[0]->joining_date!=''){
            $joining_date = $leaveCount[0]->joining_date;
            $joinDates = date("Y-m-d", strtotime($joining_date));
            $date1 = date('Y-m-d');
            $date2 = $joinDates;
            $d1=new DateTime($date2); 
            $d2=new DateTime($date1);                                  
            $Months = $d2->diff($d1); 
            $monthscount = (($Months->y) * 12) + ($Months->m);
            $total_leave_type=array(
                // 'sick_leave'=>'Sick Leave',
                'casual_leave'=>'Casual Leave',
                //'special_leave'=>'Special Leave',
                'lwp'=>'Leave Without Pay'
            );
            //Leave Type
            $leaveType = array(
                // 'sick_leave'=>'Sick Leave',
                'casual_leave'=>'Casual Leave',
                //'special_leave'=>'Special Leave',
                'lwp'=>'Leave Without Pay'
            );
         
            $sickLeaveSum = DB::select("select SUM(leave_count) as leave_count from emp_leaves where leave_type='sick_leave' and status=1 and user_id=".$user_id);
            $sickLeaveSumUse = DB::select("select SUM(leave_count) as leave_count from emp_leaves where leave_type='sick_leave' and status=0  and user_id=".$user_id);

            //$casualLeaveSum = DB::select("select SUM(leave_count) as leave_count from emp_leaves where leave_type='casual_leave' and status=1 and user_id=".$user_id);
            //$casualLeaveSumUse = DB::select("select SUM(leave_count) as leave_count from emp_leaves where leave_type='casual_leave' and status=0 and user_id=".$user_id);
            $casualLeaveSum = $this->leaveCount($user_id,1);
            $casualLeaveSumUse = $this->leaveCount($user_id,0);
            $special_leave = DB::select("select SUM(leave_count) as leave_count from emp_leaves where leave_type='special_leave' and status=1 and user_id=".$user_id);    
            $sickLeave = 0;
            if(isset($sickLeaveSum[0]->leave_count) && $sickLeaveSum[0]->leave_count != ""){
                $sickLeave = $sickLeaveSum[0]->leave_count;
            }
            $sickLeaveUse = 0;
            if(isset($sickLeaveSumUse[0]->leave_count) && $sickLeaveSumUse[0]->leave_count != ""){
                $sickLeaveUse = $sickLeaveSumUse[0]->leave_count;
            }
            $casualLeave = 0;
            if(isset($casualLeaveSum) && $casualLeaveSum != ""){
                $casualLeave = $casualLeaveSum;
            }

            $casualLeaveUse = 0;
            if(isset($casualLeaveSumUse) && $casualLeaveSumUse != ""){
                $casualLeaveUse = $casualLeaveSumUse;
            }

            $leaveCountdata = [
                
                'casualLeave'=>array(
                    'taken'=>$casualLeave,
                    'remaining'=>($leaveCount[0]->casual_leave - $casualLeave),
                    'remaining_use'=>($leaveCount[0]->casual_leave - ($casualLeaveUse+$casualLeave)),
                )
            ];
            

            if(($leaveCount[0]->casual_leave - ($casualLeaveUse+$casualLeave)) <= 0){
                $leaveType = array_filter($leaveType, function ($data) {
                    return ($data != 'Casual Leave');
                });
            }

           

            if($monthscount < 3){
                $leaveType = array(
                    'lwp' => 'Leave Without Pay'
                );
                $leaveCountdata = [
                   
                    'casualLeave' => array(
                        'taken' => 0,
                        'remaining' => 0,
                        'remaining_use' => 0
                    )
                ];
            }
        
        return view('leave/add',[
            'manager' => $manager,
            'leave' => $leave,
            'leaveType' => $leaveType,
            'leaveCount' => $leaveCount,
            'leavedata' => $leaveCountdata,
            'holiday' => $holiday,
            'user_id' => $user_id,
            'total_leave_type' => $total_leave_type
        ]);
    }else{
        return view('leave/add',[
            'manager' => [],
            'leave' => [],
            'leaveType' => [],
            'leaveCount' => 0,
            'holiday' => $holiday,
            'leavedata' => [
                
                'casualLeave' => array(
                    'taken' => 0,
                    'remaining' => 0
                )
                ],
            'total_leave_type' => $total_leave_type
        ]); 
    }
		}

        public function leaveCount($userId,$status){
            $fromDate = date('Y-m-d', strtotime('first day of january this year'));
            $todayDate= date('Y-m-d');
            $leaveCount = DB::table('emp_leaves')
            ->where('emp_leaves.status', $status)
            ->where('emp_leaves.user_id',$userId)
            ->where(function ($query) use ($fromDate, $todayDate) {
                $query->whereBetween('emp_leaves.start_date', [$fromDate, $todayDate])
                    ->orWhereBetween('emp_leaves.end_date', [$fromDate, $todayDate]);
            })->sum('leave_count');

            return $leaveCount;
        }
}
