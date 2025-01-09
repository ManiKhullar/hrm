<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmpWfh;
use DB;
use Illuminate\Support\Facades\Auth;
use DateTime;
use App\Http\Controllers\Crypt;
use App\Http\Helper\SendMail;
use App\Http\Controllers\Exception;
use Carbon\Carbon;

class WorkFromHomeController extends Controller
{


    public function wfh_add()
    {
        $user_id = Auth::user()->id;
        $manager = DB::select("select DISTINCT users.name, project_managers.manager_id as id from project_managers left join users on users.id=project_managers.manager_id where project_managers.status= '1' and project_managers.developer_id=" . $user_id);
        if (empty($manager)) {
            $manager = DB::select("select  name, id from users where employee_code='BT11101002'");
        }

        $wfh = DB::table("emp_wfhs")
            ->leftjoin("users", "users.id", '=', "emp_wfhs.user_id")
            ->where('emp_wfhs.user_id', $user_id)
            ->paginate(10, array('emp_wfhs.*', 'users.id AS users_id', 'users.name'));

        $leaveCount = DB::select('select * from emp_registrations where user_id=' . $user_id);
        $joining_date = "";
        return view('wfh/add', [
            'manager' => $manager,
            'leave' => $wfh,
            'user_id' => 0
        ]);


    }



    public function wfh_save(Request $request)
    {
        //echo "sss"; exit;
        $emailTemplate = DB::select("SELECT * FROM email_templates WHERE type = 'apply_wfh_email_template' AND status = '1'");
        $startDate = date("Y-m-d", strtotime($request->start_date));
        
         $endDate = date("Y-m-d", strtotime($request->end_date));


        $manager_data = DB::select("SELECT name,email FROM users WHERE id = $request->project_manager");
        $manager_name = $manager_data[0]->name;

        $employee_data = DB::select("SELECT name,email FROM users WHERE id = $request->user_id");
         $employee_name = $employee_data[0]->name;
         $employee_email = $employee_data[0]->email;

        $to = $manager_data[0]->email;
        $startTime = $this->returnTime($startDate,0);
        
        $endTime = $this->returnTime($endDate,1);
        //$diff = date_diff(date_create($startDate), date_create($endDate));
        
        //$daydiff = $diff->format('%R%a');
         $daydiff = ceil(($endTime - $startTime)/86400);
        
        $weekEndCount =  $this->weekendCount($request->start_date, $request->end_date);
        $publicHolyDay =  $this->getPublicHolidayCount($startDate, $endDate, 0);
        $effectiveWfh = $daydiff - $weekEndCount - $publicHolyDay;
        
         EmpWfh::create([
            'user_id' => $request->user_id,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'project_manager' => $request->project_manager,
            'leave_count' => $effectiveWfh,
            'cc' => $request->cc,
            'message' => $request->message,
            'status' => 0
        ]);

        $emails = 'hr@bluethink.in,' . $request->cc;
        $cc = trim($emails, ',');
        $leave_apply_content = "<td style='border: 1px solid #ccc;'>" . date("Y-m-d", strtotime($request->start_date)) . "</td><td style='border: 1px solid #ccc;'>" . date("Y-m-d", strtotime($request->end_date)) . "</td><td style='border: 1px solid #ccc;'>" . $effectiveWfh . "</td><td style='border: 1px solid #ccc;'>" . $request->message . "</td>";
        $employee_name_html = str_replace("{{employee_name}}", $employee_name, $emailTemplate[0]->content);
        $manager_name_html = str_replace("{{manager_name}}", $manager_name, $employee_name_html);
        $html = str_replace("{{leave_apply_content}}", $leave_apply_content, $manager_name_html);
        SendMail::sendMail($html, $emailTemplate[0]->subject, $to, 'noreply@mybluethink.in', $cc);




        $result = array(
            'status' => 'success',
            'message' => 'Wfh sucessfully applied'
        );
        return redirect()->route('wfh_add')->with('success', 'Wfh sucessfully applied.');
        // return json_encode($result);
    }

    

    public function accesswfh_list()
    {
        $managerData = DB::select("SELECT id,name FROM users WHERE role in (3,4,5)");
        $authorisedUser = array();
        foreach ($managerData as $managerDatas) {
            $authorisedUser[$managerDatas->id] = $managerDatas->name;
        }
        // print_r($authorisedUser);exit;
        $user_id = Auth::user()->id;
        $role = Auth::user()->role;
        if( $role == 4){
             $userData = DB::select("select Distinct users.id, users.name from users
            left join emp_registrations on users.id=emp_registrations.user_id
            left join project_managers as pm on pm.developer_id=users.id where pm.manager_id=".$user_id." and pm.status = '1' and emp_registrations.status='1'");
        }else{
            $userData = DB::select("select users.id, users.name from users
            left join emp_registrations on users.id=emp_registrations.user_id
            where emp_registrations.status='1'");
        }
        $managerArray = array();
        foreach ($userData as $userDatas) {
            $managerArray[$userDatas->id] = $userDatas->name;
        }
        $cDate = date("Y-m-d");
        $fromDate = date("Y-m-d", strtotime("$cDate -60 days"));


        if ($role == 4) {
            $leaves = DB::table("emp_wfhs")
                ->leftjoin("users", "users.id", '=', "emp_wfhs.user_id")
                ->leftjoin("emp_registrations", "emp_registrations.user_id", '=', "emp_wfhs.user_id")
                ->leftjoin("project_managers", "project_managers.developer_id", '=', "emp_wfhs.user_id")
                ->where('project_managers.manager_id', $user_id)
                ->where('emp_registrations.status', '1')
                ->where('project_managers.status', '1')
                ->whereDate('emp_wfhs.end_date', '>=', $fromDate)
                ->groupBy('emp_wfhs.id')
                ->orderBy('emp_wfhs.id', 'DESC')
                ->paginate(10, array(
                        'emp_wfhs.id as id',
                        'emp_wfhs.start_date',
                        'emp_wfhs.end_date',
                        'emp_wfhs.status',
                        'emp_wfhs.project_manager',
                        'emp_wfhs.approved_by',
                        'users.id as user_id',
                        'users.name',
                        'emp_wfhs.message',
                        'emp_wfhs.leave_count'
                    ));
            return view('wfh.accesslist', [
                'userData' => $userData,
                'leaves' => $leaves,
                'managerArray' => $authorisedUser,
                'authorisedUser' => $authorisedUser
            ])->with('i', (request()->input('page', 1) - 1) * 5);
        }
        if ($role == 3 || $role == 5 || $role == 6) {
            $leaves = DB::table("emp_wfhs")
                ->leftjoin("users", "users.id", '=', "emp_wfhs.user_id")
                ->leftjoin("emp_registrations", "emp_registrations.user_id", '=', "emp_wfhs.user_id")
                ->where('emp_registrations.status', '1')
                ->whereDate('emp_wfhs.end_date', '>=', $fromDate)
                ->groupBy('emp_wfhs.id')
                ->orderBy('emp_wfhs.id', 'DESC')
                ->paginate(10, array(
                    'emp_wfhs.id as id',
                    'emp_wfhs.start_date',
                    'emp_wfhs.end_date',
                    'emp_wfhs.status',
                    'emp_wfhs.project_manager',
                    'emp_wfhs.approved_by',
                    'users.id as user_id',
                    'users.name',
                    'emp_wfhs.message',
                    'emp_wfhs.leave_count'
                ));
            return view('wfh.accesslist', [
                'userData' => $userData,
                'leaves' => $leaves,
                //'managerArray' => $managerArray,
                'managerArray' =>$authorisedUser,
                'authorisedUser' => $managerArray
            ])->with('i', (request()->input('page', 1) - 1) * 5);
        }
    }

    

   

    public function getPublicHolidayCount($startDate, $endDate, $count)
    {
        $startDate = date("Y-m-d", strtotime($startDate));
        $endDate = date("Y-m-d", strtotime($endDate));
        $holidays = DB::select("select date from holidays where `date` between '$startDate' and '$endDate' and status= '1'");
       $holidayCount = 0;
       
        foreach ($holidays as $holiday) {
           
            $dayOfWeek = date('w', strtotime($holiday->date));
          
           if ($dayOfWeek>0 || $dayOfWeek < 5) {
				$holidayCount++ ;
			}
        }
        
        return $holidayCount;

    }

    
    public function weekendCount($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate);

        $endDate = Carbon::parse($endDate);
        $days = $startDate->diffInDaysFiltered(function (Carbon $date) {
            return !$date->isWeekday();
        }, $endDate);
        return $days;
    }

    public function wfh_edit($edit_id)
    {
        $manager = DB::select('select * from managers');
        $leave = DB::select('select * from emp_wfhs where id='.$edit_id);
        return view('wfh/edit',[
            'manager'=> $manager,
            'leave'=> $leave
        ]);
    }

    public function wfh_update(Request $request, $id)
    {
        $request->validate([
            'message' => 'required',
        ]);
        
        $leave = EmpWfh::where('id', $id)->firstOrFail();
        if($request->status >0){
            $leave->update([
                'message' => $request->message,
                'status' => $request->status,
                'approved_by' => Auth::user()->id
            ]);
        }else{
        $leave->update([
            'message' => $request->message,
            'status' => $request->status
        ]);
    }
        return redirect()->route('wfh_add')->with('success','Wfh Updated Successfully.');
    }
    public function accesswfh_edit($edit_id)
    {
        $manager = DB::select('select * from managers');
        $leave = DB::select('select * from emp_wfhs where id='.$edit_id);
        return view('wfh/editaccessleave',[
            'manager'=> $manager,
            'leave'=> $leave
        ]);
    } 

    public function wfhfilter(Request $request)
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
        

        if($role == 4)
        {
            if(!empty($request->emp_id))
            {
                $leaves = DB::table("emp_wfhs")
                    ->leftjoin("users", "users.id", '=', "emp_wfhs.user_id")
                    ->leftjoin("emp_registrations", "emp_registrations.user_id", '=', "emp_wfhs.user_id")
                    ->leftjoin("project_managers", "project_managers.developer_id", '=', "emp_wfhs.user_id")
                    ->where('emp_registrations.status','1')
                    ->where('project_managers.manager_id',$user_id)
                    ->Where('users.id', $request->emp_id)
                    ->WhereBetween('emp_wfhs.start_date',[$from, $to])
                    ->distinct('emp_wfhs.id')
                    ->orderBy('emp_wfhs.id', 'DESC')
                    ->paginate(10,array('emp_wfhs.id as id','emp_wfhs.start_date','emp_wfhs.end_date',
                    'emp_wfhs.status','emp_wfhs.message','emp_wfhs.project_manager',
                    'users.id as user_id', 'users.name','emp_wfhs.leave_count','emp_wfhs.approved_by'));
            } else {
                $leaves = DB::table("emp_wfhs")
                    ->leftjoin("users", "users.id", '=', "emp_wfhs.user_id")
                    ->leftjoin("emp_registrations", "emp_registrations.user_id", '=', "emp_wfhs.user_id")
                    ->leftjoin("project_managers", "project_managers.developer_id", '=', "emp_wfhs.user_id")
                    ->where('emp_registrations.status','1')
                    ->where('project_managers.manager_id',$user_id)
                    ->WhereBetween('emp_wfhs.start_date',[$from, $to])
                    ->distinct('emp_wfhs.id')
                    ->orderBy('emp_wfhs.id', 'DESC')
                    ->paginate(10,array('emp_wfhs.id as id','emp_wfhs.start_date','emp_wfhs.end_date',
                    'emp_wfhs.status','emp_wfhs.message','emp_wfhs.project_manager',
                    'users.id as user_id', 'users.name','emp_wfhs.leave_count','emp_wfhs.approved_by'));
            }
            if(!empty($request->emp_id)){
                $leaves->appends(['emp_id'=>$request->emp_id]);
            }

            if(!empty($from) && !empty($to)){
                $leaves->appends(['from'=>$request->from]);
                $leaves->appends(['to'=>$request->to]);
            }
            return view('wfh.accesslist',[
                'userData' => $userData,
                'leaves'=> $leaves,
                'managerArray' => $managerArray,
                'authorisedUser' => $authorisedUser,
                ]);
        } 
        if($role == 3 || $role == 5 || $role == 6)
        {
            if(!empty($request->emp_id))
            {
                $leaves = DB::table("emp_wfhs")
                    ->leftjoin("users", "users.id", '=', "emp_wfhs.user_id")
                    ->leftjoin("emp_registrations", "emp_registrations.user_id", '=', "emp_wfhs.user_id")
                    ->leftjoin("project_managers", "project_managers.developer_id", '=', "emp_wfhs.user_id")
                    ->where('emp_registrations.status','1')
                    ->Where('users.id', $request->emp_id)
                    ->WhereBetween('emp_wfhs.start_date',[$from, $to])
                    ->distinct('emp_wfhs.id')
                    ->orderBy('emp_wfhs.id', 'DESC')
                    ->paginate(10,array('emp_wfhs.id as id','emp_wfhs.start_date','emp_wfhs.end_date',
                    'emp_wfhs.status','emp_wfhs.message','emp_wfhs.project_manager',
                    'users.id as user_id', 'users.name','emp_wfhs.leave_count','emp_wfhs.approved_by'));
            }else{
                $leaves = DB::table("emp_wfhs")
                    ->leftjoin("users", "users.id", '=', "emp_wfhs.user_id")
                    ->leftjoin("emp_registrations", "emp_registrations.user_id", '=', "emp_wfhs.user_id")
                    ->leftjoin("project_managers", "project_managers.developer_id", '=', "emp_wfhs.user_id")
                    ->where('emp_registrations.status','1')
                    ->WhereBetween('emp_wfhs.start_date',[$from, $to])
                    ->distinct('emp_wfhs.id')
                    ->orderBy('emp_wfhs.id', 'DESC')
                    ->paginate(10,array('emp_wfhs.id as id','emp_wfhs.start_date','emp_wfhs.end_date',
                    'emp_wfhs.status','emp_wfhs.message','emp_wfhs.project_manager',
                    'users.id as user_id', 'users.name','emp_wfhs.leave_count','emp_wfhs.approved_by'));
            }

            if(!empty($request->emp_id)){
                $leaves->appends(['emp_id'=>$request->emp_id]);
            }

            if(!empty($from) && !empty($to)){
                $leaves->appends(['from'=>$request->from]);
                $leaves->appends(['to'=>$request->to]);
            }
               
            return view('wfh.accesslist',[
                'userData' => $userData,
                'leaves'=> $leaves,
                'managerArray' => $managerArray,
                'authorisedUser' => $authorisedUser
                ]);
        }
    }

    public function update_wfh(Request $request,$id)
    {
        $request->validate([
        'status' =>  'required',
        ]);
        $role = Auth::user()->role;
        $approvalId = Auth::user()->id;
        $manag_name = Auth::user()->where('id', $approvalId)->firstOrFail();
        $leave = EmpWfh::where('id', $id)->firstOrFail();
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
            $emailTemplate = DB::select("SELECT * FROM email_templates WHERE type = 'approved_wfh_email_template' AND status = '1'");
        }elseif($request->status == 2){
            $emailTemplate = DB::select("SELECT * FROM email_templates WHERE type = 'wfh_reject_email_template' AND status = '1'");
        }else{
            $emailTemplate = DB::select("SELECT * FROM email_templates WHERE type = 'apply_wfh_email_template' AND status = '1'");
        }

        $leave_apply_content = "<td style='border: 1px solid #ccc;'></td><td style='border: 1px solid #ccc;'>".$leave->start_date."</td><td style='border: 1px solid #ccc;'>".$leave->end_date."</td><td style='border: 1px solid #ccc;'>".$leave->leave_count."</td><td style='border: 1px solid #ccc;'>".$request->message."</td><td style='border: 1px solid #ccc;'>".$leave_status[$request->status]."</td>";
        $employee_name_html = str_replace("{{employee_name}}",$employee_name,$emailTemplate[0]->content);
        if($request->status == 0){
            $manager_datas = DB::select("SELECT name,email FROM users WHERE id = $leave->project_manager");
            $manager_name = $manager_datas[0]->name;
            $employee_name_html = str_replace("{{manager_name}}",$manager_name,$employee_name_html);
        }
        $updateManager = str_replace("{{manager_name}}",$manag_name->name,$employee_name_html);
        $html = str_replace("{{leave_apply_content}}",$leave_apply_content,$updateManager);
        SendMail::sendMail($html, $emailTemplate[0]->subject, $to, 'noreply@mybluethink.in', $leave->cc);
        
        EmpWfh::where('id', $id)
       ->update([
           'approved_by' => Auth::user()->id,
           'comment' => $request->comment,
            'status' => $request->status
        ]);
        if($role == 2){
            return redirect()->route('wfh_add')->with('success','Wfh Updated Successfully.');
        } elseif($role == 4 || $role == 5 || $role == 6 || $role == 3){
            return redirect()->route('accesswfhlist')->with('success','Wfh Updated Successfully.');
        }
    }
private function returnTime($date,$flag){
    if($flag <=0):
        
    $time = mktime(0,0,0,date('m',strtotime($date)),date('d',strtotime($date)),date('Y',strtotime($date)));
    else:
        
    $time = mktime(23,59,59,date('m',strtotime($date)),date('d',strtotime($date)),date('Y',strtotime($date)));
    endif;
    return $time;
}
}
