<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use DateTime;
use Illuminate\Support\Facades\Auth;
use App\Models\SessionLogin;
use App\Models\EmpLoginHours;
use DatePeriod;

class LoginSession extends Controller
{
    public function loginsession_list()
    {
        date_default_timezone_set('Asia/Kolkata');
        $userData =  DB::select("select users.id, users.name from users
            left join emp_registrations on users.id=emp_registrations.user_id
            where emp_registrations.status='1'");
        $logindata = DB::table('login_session')
            ->LEFTJOIN('users','users.id','=','login_session.user_id')
            ->LEFTJOIN('emp_registrations','emp_registrations.user_id','=','login_session.user_id')
            ->LEFTJOIN('emp_communications','emp_communications.user_id','=','login_session.user_id')
            ->whereMonth('login_session.created_at', date('m'))
            ->whereYear('login_session.created_at', date('Y'))
            ->where('emp_registrations.status','1')
            ->orderBy('login_session.id','DESC')
            ->paginate(30,array('login_session.id','login_session.work_hours','login_session.location','emp_communications.mobile_number','users.name','users.email','login_session.created_at'));

        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $currentDateLogin = DB::select("SELECT users.id FROM login_session 
            left join users on users.id=login_session.user_id 
            WHERE YEAR(CAST(login_session.created_at AS DATE))='$year' 
            and MONTH(CAST(login_session.created_at AS DATE))='$month'
            and DAY(CAST(login_session.created_at AS DATE))='$day'");

        $loginId = [];
        $allId = [];
        foreach ($currentDateLogin as $key => $value) {
            $loginId[] = $value->id;
        }
        foreach ($userData as $key => $value1) {
            $allId[] = $value1->id;
        }
        $notLogin = array_diff($allId, $loginId);
        $role = array('2','3','4','6','7');
        $notLoginData = DB::select("select users.name, users.email, users.employee_code, emp_communications.mobile_number from users 
            left join emp_communications ON  users.id=emp_communications.user_id 
            where users.role IN (" . implode(',', $role) . ") and users.id IN (" . implode(',', $notLogin) . ")");

        $todayLeave = DB::select("SELECT * FROM emp_leaves WHERE status in ('0','1')");
        $userdatas = array();
        foreach($todayLeave as $todayLeavedata){
            $enddate = new DateTime($todayLeavedata->end_date);
            $start = new DateTime($todayLeavedata->start_date);
            $day = $start->diff($enddate)->format("%r%a");
            $checkLeave = false;
            for($i=0; $i<=$day; $i++){
                $checkdate = date('Y-m-d', strtotime($todayLeavedata->start_date. ' +'.$i.' day'));
                if($checkdate===date('Y-m-d')){
                    $checkLeave = true;
                    $userdatas[$todayLeavedata->user_id]=$checkLeave;
                }
            }
        }

        $arrayData = implode(',', array_keys($userdatas));
        $empLeavedata = [];
        if($arrayData != ''){
            $empLeavedata = DB::select("select users.name, users.email, users.employee_code, emp_communications.mobile_number from users 
                left join emp_communications ON  users.id=emp_communications.user_id
                left join emp_registrations ON users.id=emp_registrations.user_id
                where emp_registrations.status='1' and users.id IN (" . $arrayData . ")");
        }
        return view('loginsession.list',[
            'userData' => $userData,
            'logindata' => $logindata,
            'notLoginData' => $notLoginData,
            'empLeavedata' => $empLeavedata
        ]);
    }

    public function loginsession_edit($id){
        $sessionLoginData = DB::table('login_session')
            ->LEFTJOIN('users','users.id','=','login_session.user_id')
            ->where('login_session.id',$id)
            ->select('login_session.id','login_session.location','users.name','users.email','login_session.created_at')
            ->get();
        return view('loginsession.loginsessionedit', compact('sessionLoginData'));
    }

    public function loginsession_update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'date' => 'required',
            'location' => 'required',
        ]);
        
        if ((Auth::user()->role) == 3 || (Auth::user()->role) == 5) {
            $sessionLoginUpdate = SessionLogin::where('id', $id)->firstOrFail();
            $sessionLoginUpdate->update([
                'location' =>  $request->location,
            ]);
        }
        return redirect()->route('loginsessionlist')->with('success','Login List Updated Successfully');
    }

    public function loginsession_search(Request $request)
    {
        $userData =  DB::select("select users.id, users.name from users
            left join emp_registrations on users.id=emp_registrations.user_id
            where emp_registrations.status='1'");
        if(!empty($request->emp_id) && !empty($request->day)){
            $logindata = DB::table('login_session')
                ->LEFTJOIN('users','users.id','=','login_session.user_id')
                ->LEFTJOIN('emp_registrations','emp_registrations.user_id','=','login_session.user_id')
                ->LEFTJOIN('emp_communications','emp_communications.user_id','=','login_session.user_id')
                ->whereYear('login_session.created_at', $request->year)
                ->whereMonth('login_session.created_at', $request->month)
                ->whereDay('login_session.created_at', $request->day)
                ->where('users.id',$request->emp_id)
                ->where('emp_registrations.status','1')
                ->orderBy('login_session.id','DESC')
                ->paginate(30,array('login_session.id','login_session.work_hours','login_session.location','emp_communications.mobile_number','users.name','users.email','login_session.created_at'));
        } elseif (!empty($request->emp_id)) {
            $logindata = DB::table('login_session')
                ->LEFTJOIN('users','users.id','=','login_session.user_id')
                ->LEFTJOIN('emp_registrations','emp_registrations.user_id','=','login_session.user_id')
                ->LEFTJOIN('emp_communications','emp_communications.user_id','=','login_session.user_id')
                ->whereYear('login_session.created_at',$request->year)
                ->whereMonth('login_session.created_at',$request->month)
                ->where('users.id',$request->emp_id)
                ->where('emp_registrations.status','1')
                ->orderBy('login_session.id','DESC')
                ->paginate(30,array('login_session.id','login_session.work_hours','login_session.location','emp_communications.mobile_number','users.name','users.email','login_session.created_at'));
        } elseif (!empty($request->day)) {
            $logindata = DB::table('login_session')
                ->LEFTJOIN('users','users.id','=','login_session.user_id')
                ->LEFTJOIN('emp_registrations','emp_registrations.user_id','=','login_session.user_id')
                ->LEFTJOIN('emp_communications','emp_communications.user_id','=','login_session.user_id')
                ->whereDay('login_session.created_at',$request->day)
                ->whereYear('login_session.created_at',$request->year)
                ->whereMonth('login_session.created_at',$request->month)
                ->where('emp_registrations.status','1')
                ->orderBy('login_session.id','DESC')
                ->paginate(30,array('login_session.id','login_session.work_hours','login_session.location','emp_communications.mobile_number','users.name','users.email','login_session.created_at'));
        } else {
            $logindata = DB::table('login_session')
                ->LEFTJOIN('users','users.id','=','login_session.user_id')
                ->LEFTJOIN('emp_registrations','emp_registrations.user_id','=','login_session.user_id')
                ->LEFTJOIN('emp_communications','emp_communications.user_id','=','login_session.user_id')
                ->whereYear('login_session.created_at',$request->year)
                ->whereMonth('login_session.created_at',$request->month)
                ->where('emp_registrations.status','1')
                ->orderBy('login_session.id','DESC')
                ->paginate(30,array('login_session.id','login_session.work_hours','login_session.location','emp_communications.mobile_number','users.name','users.email','login_session.created_at'));
        }

        if(!empty($request->emp_id)){
            $logindata->appends(['emp_id'=>$request->emp_id]);
        }
        if(!empty($request->day)){
            $logindata->appends(['day'=>$request->day]);
        }
        if(!empty($request->year)){
            $logindata->appends(['year'=>$request->year]);
        }
        if(!empty($request->month)){
            $logindata->appends(['month'=>$request->month]);
        }

        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $currentDateLogin = DB::select("SELECT users.id FROM login_session 
            left join users on users.id=login_session.user_id 
            WHERE YEAR(CAST(login_session.created_at AS DATE))='$year' 
            and MONTH(CAST(login_session.created_at AS DATE))='$month'
            and DAY(CAST(login_session.created_at AS DATE))='$day'");

        $loginId = [];
        $allId = [];
        foreach ($currentDateLogin as $key => $value) {
            $loginId[] = $value->id;
        }
        foreach ($userData as $key => $value1) {
            $allId[] = $value1->id;
        }
        $notLogin = array_diff($allId, $loginId);
        $role = array('2','3','4','6','7');
        $notLoginData = DB::select("select users.name, users.email, users.employee_code, emp_communications.mobile_number from users left join emp_communications ON  users.id=emp_communications.user_id where users.role IN (" . implode(',', $role) . ") and users.id IN (" . implode(',', $notLogin) . ")");

        $todayLeave = DB::select("SELECT * FROM emp_leaves WHERE status in ('0','1')");
        $userdatas = array();
        foreach($todayLeave as $todayLeavedata){
            $enddate = new DateTime($todayLeavedata->end_date);
            $start = new DateTime($todayLeavedata->start_date);
            $day = $start->diff($enddate)->format("%r%a");
            $checkLeave = false;
            for($i=0; $i<=$day; $i++){
                $checkdate = date('Y-m-d', strtotime($todayLeavedata->start_date. ' +'.$i.' day'));
                if($checkdate===date('Y-m-d')){
                    $checkLeave = true;
                    $userdatas[$todayLeavedata->user_id]=$checkLeave;
                }
            }
        }
        $arrayData = implode(',', array_keys($userdatas));
        $empLeavedata = [];
        if($arrayData != ''){
            $empLeavedata = DB::select("select users.name, users.email, users.employee_code, emp_communications.mobile_number from users 
                left join emp_communications ON  users.id=emp_communications.user_id
                left join emp_registrations ON users.id=emp_registrations.user_id
                where emp_registrations.status='1' and users.id IN (" . $arrayData . ")");
        }

        return view('loginsession.list',[
            'userData' => $userData,
            'logindata'=> $logindata,
            'notLoginData' => $notLoginData,
            'empLeavedata' => $empLeavedata
        ]);
    }

    public function emplogin_hours()
    {
        $user_id = Auth::user()->id;
        $userData = DB::select("select emp_shift.timezone from users 
            left join emp_shift ON  users.emp_shift_id=emp_shift.id where users.id=$user_id");

        date_default_timezone_set($userData[0]->timezone);
        $sessionLogin = SessionLogin::where('user_id',$user_id)
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->whereDay('created_at', date('d'))->first();

        if (empty($sessionLogin->created_at)) {
            return redirect()->route('logout');
        }
        $currDateTime = new DateTime();
        $time_login = new DateTime($sessionLogin->created_at);
        $interval = $currDateTime->diff($time_login)->format('%h'.'.'.'%I');
        $sessionLogin->update([
            'status' =>  'Logout',
            'work_hours' => $interval
        ]);

        $sessionLoginData = DB::table('login_session')
            ->LEFTJOIN('users','users.id','=','login_session.user_id')
            ->where('login_session.user_id',$user_id)
            ->whereMonth('login_session.created_at', date('m'))
            ->whereYear('login_session.created_at', date('Y'))
            ->orderBy('login_session.id','DESC')
            ->paginate(25,array('login_session.comment','login_session.location','login_session.work_hours','login_session.created_at','login_session.updated_at'));
        return view('loginsession.loginhours',[
            'sessionLoginData' => $sessionLoginData
        ]);
    }
}
