<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Redirect;
use App\Models\SessionLogin;
use Illuminate\Support\Facades\Auth;
use DateTime;
use App\Models\AssetEmp;
use App\Http\Helper\SendMail;

class AuthController extends Controller
{
    public function index()
    {
        if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')   
            $url = "https://";   
        else  
            $url = "http://";   
        // Append the host(domain name, ip) to the URL.   
        $url.= $_SERVER['HTTP_HOST'];   
        
        // Append the requested resource location to the URL   
        $url.= $_SERVER['REQUEST_URI'];    

        if($_SERVER['REQUEST_URI'] == '/'){
            return Redirect::to($url.'index');
        }
     
        return view('auth/login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'employee_code' => 'required',
            'password' => 'required',
            'location' => 'required'
        ]);

        $dataUser = DB::select("select * from emp_registrations where status='1' and employee_code='$request->employee_code'");
        if(!empty($dataUser))
        {
            if(\Auth::attempt($request->only('employee_code','password'))){
                $this->logindata_save($request->employee_code, $request->location);
                return redirect('dashboard');
            }
        }

        return redirect('index')->withError('Invalid Credentials');
    }

    public function logindata_save($data, $location)
    {
        $userData = DB::select("select users.id, emp_shift.shift_name, emp_shift.timezone from users 
            left join emp_shift ON  users.emp_shift_id=emp_shift.id
            where users.employee_code='$data'");

        date_default_timezone_set($userData[0]->timezone);
        $existData = SessionLogin::where('user_id', $userData[0]->id)
            ->whereYear('created_at', date('Y'))
            ->whereMonth('created_at', date('m'))
            ->whereDay('created_at', date('d'))->first();

        if(empty($existData)){
            SessionLogin::create([
                'user_id' => $userData[0]->id,
                'location' => $location,
                'status' =>  'Login'
            ]);
        }else {
            $existData->update([
                // 'location' => $location,
                'status' =>  'Login',
            ]);
        }
    }

    public function emp_assets(Request $request)
    {
        $user_id = Auth::user()->id;
        $userName = Auth::user()->name;
        $to = Auth::user()->email;
        $from = 'noreply@mybluethink.in';
        $cc = 'shubham@bluethink.in';

        $emailTemplate = DB::select("SELECT * FROM email_templates WHERE type = 'emp_assets' AND status = '1'");
        $comment = str_replace("{{##USERNAME$}}",$userName,$emailTemplate[0]->content);
        $html = str_replace("{{##COMMENT$}}",$request->comment,$comment);

        if(empty($alreadyData)){
            AssetEmp::create([
                'user_id' => $user_id,
                'comment' => $request->comment
            ]);
            SendMail::sendMail($html, $emailTemplate[0]->subject, $to, $from, $cc);
        }
    }

    public function register_view(){
        return view('auth.register');
    }

    public function register(Request $request){
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users|email',
            'employee_code' => 'required',
            'role' => 'required',
            'password' => 'required',
        ]);

        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'employee_code'=>$request->employee_code,
            'role'=>$request->role,
            'password'=>\Hash::make($request->password)
        ]);

        if(\Auth::attempt($request->only('employee_code','password'))){
           return redirect('dashboard');
        }

        return redireact('register')->withError('Error');
    }

    public function dashboard()
    {
        $employee_code = Auth::user()->employee_code;
        $assets = array();
		$ch = curl_init('http://itsd.bluethinkinc.com/api/getEmpAsset?emp_id='.$employee_code);
	    	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $response = curl_exec($ch);
	    curl_close($ch);
	    $assets = json_decode($response,true);

        $currrentYear=date('Y');
        $currrentMonth=date('m');
        $lastDateOfMonth = cal_days_in_month(CAL_GREGORIAN, $currrentMonth, $currrentYear);
        $start_date = $currrentYear ."-". $currrentMonth ."-01";
        $end_date = $currrentYear ."-". $currrentMonth ."-" .$lastDateOfMonth;

       //for next month data;
        $start_date_next = strtotime($start_date);
        $start_date_next_data = date("Y-m-d", strtotime("+1 month", $start_date_next));

        $end_date_next_month = date("m",strtotime($start_date_next_data));
        $end_date_next_year = date("Y",strtotime($start_date_next_data));
        $lastDateOfMonth_next = cal_days_in_month(CAL_GREGORIAN, $end_date_next_month, $end_date_next_year);
        $end_date_next_data = $end_date_next_year."-".$end_date_next_month."-".$lastDateOfMonth_next;

      
        $work_anversary = DB::select("SELECT * FROM emp_registrations
            left join users on emp_registrations.user_id=users.id
            WHERE emp_registrations.status='1' AND YEAR(emp_registrations.joining_date) < $currrentYear AND DATE_FORMAT(emp_registrations.joining_date, '%m-%d')
            BETWEEN DATE_FORMAT('$start_date', '%m-%d') and DATE_FORMAT('$end_date', '%m-%d') order by DATE_FORMAT(joining_date, '%m-%d') ASC");

        $work_anversary_next = DB::select("SELECT * FROM emp_registrations
            left join users on emp_registrations.user_id=users.id
            WHERE emp_registrations.status='1' AND DATE_FORMAT(joining_date, '%m-%d') BETWEEN DATE_FORMAT('$start_date_next_data', '%m-%d') and DATE_FORMAT('$end_date_next_data', '%m-%d') order by DATE_FORMAT(joining_date, '%m-%d') ASC");

        $dob = DB::select("SELECT * FROM emp_registrations
            left join users on emp_registrations.user_id=users.id
            WHERE emp_registrations.status='1' AND DATE_FORMAT(dob, '%m-%d') BETWEEN DATE_FORMAT('$start_date', '%m-%d') and DATE_FORMAT('$end_date', '%m-%d') order by DATE_FORMAT(dob, '%m-%d') ASC");

        $dob_next = DB::select("SELECT * FROM emp_registrations
            left join users on emp_registrations.user_id=users.id
            WHERE emp_registrations.status='1' AND DATE_FORMAT(dob, '%m-%d') BETWEEN DATE_FORMAT('$start_date_next_data', '%m-%d') and DATE_FORMAT('$end_date_next_data', '%m-%d') order by DATE_FORMAT(dob, '%m-%d') ASC");
        
        $startDate = date('Y-m-d 00:00:00');
        $endDate = date('Y-m-d 23:59:59'); 
        /*$sqlQuery = "Select ls.location,u.name,u.employee_code from users as u 
        inner join login_session as ls on ls.user_id = u.id where ls.location ='WFH' 
        and ls.status='login' and ls.created_at between '".$startDate."' and '".$endDate."'"; */
        $sqlQuery = "Select el.start_date,el.end_date,u.name,u.employee_code from users as u inner join emp_wfhs as el on el.user_id = u.id where el.status !=2 AND  CURDATE() BETWEEN el.start_date AND  el.end_date";

        $workFromHome = DB::Select($sqlQuery);

        /*echo $leaveSql = "Select el.start_date,el.end_date,u.name,u.employee_code from users as u 
        inner join emp_leaves as el on el.user_id = u.id where el.status !=2 AND (el.start_date between '".date("Y-m-d")."' and '".date("Y-m-d")."' OR
         el.end_date between '".date("Y-m-d")."' and '".date("Y-m-d")."') ";*/
         $leaveSql = "Select el.start_date,el.end_date,u.name,u.employee_code from users as u inner join emp_leaves as el on el.user_id = u.id where el.status !=2 AND  CURDATE() BETWEEN el.start_date AND  el.end_date";
        $onLeave = DB::Select($leaveSql);
        
        $user_id = Auth::user()->id;
        $empAsset = DB::select("select * from emp_asset where user_id=$user_id order by updated_at DESC limit 1");
        $interval = '';
        if(!empty($empAsset))
        {
            $currDateTime = new DateTime();
            $last_updated = new DateTime($empAsset[0]->updated_at);
            $interval = $currDateTime->diff($last_updated)->format('%a');
        }
        $empNotice = DB::select("select * from emp_notice where status='1'");

        return view('dashboard',[
            'empAsset' => $empAsset,
            'interval' => $interval,
            'assets' => $assets,
            'onLeave' =>$onLeave,
            'work_anversary' => $work_anversary,
            'work_anversary_next' => $work_anversary_next,
            'dob' => $dob,
            'wfh' => $workFromHome,
            'dob_next' => $dob_next,
            'empNotice' => $empNotice
        ]);
    }

    public function logout(){
        \Session::flush();
        \Auth::logout();
        return redirect('index');
    }

}
