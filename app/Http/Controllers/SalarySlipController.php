<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\EmpSalary;
use DateInterval;
use Barryvdh\DomPDF\Facade\Pdf;
use DateTime;
use DatePeriod;
use Illuminate\Support\Facades\Redirect;
class SalarySlipController extends Controller
{
    public function salary_slip()
    {
        $user_id = Auth::user()->id;
        $userData = DB::select("select users.name, users.employee_code,emp_salary.year,emp_salary.month,emp_salary.created_at, emp_salary.id from emp_salary
            left join  users on users.id=emp_salary.user_id where emp_salary.user_id=$user_id order by emp_salary.id DESC limit 6");
        return view('salary/view',['userData'=>$userData]);
    }

    public function salary_generate()
    {
        return view('salary/generate');
    }

    public function salary_cron(Request $request)
    {
        $empSalary = DB::select('select * from emp_salary');
        $currrentYear=date($request->year);
        $currrentMonth=date($request->month);
        $noOfWorkingDays = $this->noOfWorkingDays($currrentYear, $currrentMonth, array(0, 6));
        $lastDateOfMonth = cal_days_in_month(CAL_GREGORIAN, $currrentMonth, $currrentYear);
        $start_date = $currrentYear ."-". $currrentMonth ."-01";
        $end_date = $currrentYear ."-". $currrentMonth ."-" .$lastDateOfMonth;

        $filePath = public_path('employee_salary/emp_salary.csv');
        $file = fopen($filePath , 'r');
        if (!$file) {
            fclose($file);
            throw new \Exception("Could not open file: {$filePath}");
        }

        $columnHeaders = [];
        $counter = 0;
        while ($data = fgetcsv($file, 100000, ",")) {
            if ($counter == 0) {
                $columnHeaders = $data;
                $counter++;
                continue;
            }
            $dataValue = array_combine($columnHeaders, $data);

            if(trim($dataValue['employee_code']) === ''){
                continue;
            }else{
                $user_id = DB::select("SELECT id FROM users WHERE employee_code = '".trim($dataValue['employee_code'])."'");
                
                if(!empty($user_id) )
                {
				    $noOfLeave = DB::select("select * from emp_leaves where (leave_type='casual_leave' or leave_type='sick_leave') and start_date >= '$start_date' and end_date <= '$end_date' and status=1 and user_id=".$user_id[0]->id);
                    $lwpLeave = DB::select("select * from emp_leaves where leave_type='lwp' and status=1 and user_id=".$user_id[0]->id);
                    $sql = "select DISTINCT(select_date)  from timesheet where select_date between '$start_date' AND '$end_date' and user_id=".$user_id[0]->id;
                    $sandwitch = $this->getSandwitch($start_date,$end_date,$user_id[0]->id);
                    $timeSheet = DB::select($sql);
                    $total_number_leave = array_sum(array_column($noOfLeave,'leave_count'));
                    $total_number_leave_lwp = array_sum(array_column($lwpLeave,'leave_count'));
                    $noOfAbsent = $noOfWorkingDays - ($total_number_leave + count($timeSheet) + $this->holiday($start_date,$end_date));
					if($noOfAbsent < 0):
					    $noOfAbsent = 0;
					endif;
                    
                   $creditSalary = ($dataValue['salary']) - ((($dataValue['salary'])/$lastDateOfMonth)*$noOfAbsent);
                    
                    $alreadyData = DB::select("SELECT * FROM emp_salary WHERE user_id =". $user_id[0]->id." AND year=".$currrentYear." AND month='".$currrentMonth."'");
                    
                    if(empty($alreadyData)){
                        EmpSalary::create([
                            'user_id' => $user_id[0]->id,
                            'month' =>$currrentMonth,
                            'year' =>$currrentYear,
                            'credit_salary' => round($creditSalary)
                        ]);
                    }else{
                        DB::table('emp_salary')
                            ->where('id', $alreadyData[0]->id)
                            ->update([
                                'month' =>$currrentMonth,
                                'year' =>$currrentYear,
                                'credit_salary' => round($creditSalary)
                            ]);
                    }
                }else {
                    continue;
                }
            }
            $counter++;
        }
        fclose($file);
        return redirect()->route('salarygenerate')->with('success','All Employee Salary Generated Successfully.');
    }

    public function salary_import(){
        return view('salary.index');
    }

    public function getSandwitch($start_date,$end_date,$user_id){
        
        $lwpLeave = DB::select("select * from emp_leaves where start_date >= '$start_date' and end_date <= '$end_date' and status=1 and user_id=".$user_id);
        $count = 0;
        foreach($lwpLeave as $lwpLeavedata){
            $start = new DateTime($lwpLeavedata->start_date);
            $end = new DateTime($lwpLeavedata->end_date);
            $interval = new DateInterval('P1D'); // 1 day interval
            $period = new DatePeriod($start, $interval, $end);
           
            foreach ($period as $date) {
                if ($date->format('l') === 'Sunday') {
                    $count++;
                }
            }
        }

        return $count;
    }

    public function emp_salary_import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);
        if((Auth::user()->role) == 6 || (Auth::user()->role) == 3 || (Auth::user()->role) == 5){
            $path = public_path('employee_salary/');
            !is_dir($path) &&
                mkdir($path, 0777, true);

            $data = $request->file('file');
            $data->move($path, $data->getClientOriginalName());
            
            return redirect()->route('salaryimport')->with('success','Employee Salary Data Imported Successfully.');
        }else{
            return redirect()->route('salaryimport')->with('success','You are not authorized.');
        }
    }

    public function salary_list()
    {
        $users = DB::select("SELECT users.id, users.name FROM users
            LEFT JOIN emp_registrations ON users.id=emp_registrations.user_id
            WHERE emp_registrations.status='1'");

        $empData = DB::table('emp_salary')
            ->leftjoin('users', 'users.id', '=', 'emp_salary.user_id')
            ->leftjoin('emp_registrations','emp_registrations.user_id','=','emp_salary.user_id')
            ->where('emp_registrations.status','1')
            ->orderBy('emp_salary.created_at', 'DESC')
            ->paginate(10,array('emp_salary.id','users.name','users.employee_code','users.email','emp_salary.credit_salary','emp_salary.month','emp_salary.year'));
        
        return view('salary.list',[
            'users'=>$users,
            'empData'=>$empData
        ])->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function salarylist_filter(Request $request)
    {
        $request->validate([
            'month' => 'required',
            'year' => 'required'
            ]);

        $users = DB::select("SELECT users.id, users.name FROM users
            LEFT JOIN emp_registrations ON users.id=emp_registrations.user_id
            WHERE emp_registrations.status='1'");

        if(!empty($request->emp_id)){
            $empData = DB::table('emp_salary')
                ->leftjoin('users', 'users.id', '=', 'emp_salary.user_id')
                ->where('users.id',$request->emp_id)
                ->where('emp_salary.year', $request->year)
                ->where('emp_salary.month', $request->month)
                ->paginate(10,array('emp_salary.id','users.name','users.employee_code','users.email','emp_salary.credit_salary','emp_salary.year' ,'emp_salary.month'));
        } else {
            $empData = DB::table('emp_salary')
                ->leftjoin('users', 'users.id', '=', 'emp_salary.user_id')
                ->leftjoin('emp_registrations','emp_registrations.user_id','=','emp_salary.user_id')
                ->where('emp_registrations.status','1')
                ->where('emp_salary.year', $request->year)
                ->where('emp_salary.month', $request->month)
                ->paginate(10,array('emp_salary.id','users.name','users.employee_code','users.email','emp_salary.credit_salary','emp_salary.year' ,'emp_salary.month'));
        }
        if(!empty($request->emp_id)){
            $empData->appends(['emp_id'=>$request->emp_id]);
        }
        if(!empty($request->year) && !empty($request->month)){
            $empData->appends(['year'=>$request->year]);
            $empData->appends(['month'=>$request->month]);
        }

        return view('salary.list',[
            'users'=>$users,
            'empData'=>$empData
        ])->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function noOfWorkingDays($year, $month, $ignore) {
        $count = 0;
        $counter = mktime(0, 0, 0, $month, 1, $year);
        while (date("n", $counter) == $month) {
            if (in_array(date("w", $counter), $ignore) == false) {
                $count++;
            }
            $counter = strtotime("+1 day", $counter);
        }
        return $count;
    }

    public function isWeekend($date) {
        $weekDay = date('w', strtotime($date));
        if($weekDay == 0 || $weekDay == 6){
            return 0;
        }else{
            return 1;
        };
    }

    public function holiday($start_date,$end_date)
    {
        $sql = "select * from holidays where date between '$start_date' AND '$end_date' and status='1'";
        $holidays = DB::select($sql);
        $dayscount = array();
        foreach($holidays as $datas){
            $dayscount[] = $this->isWeekend($datas->date);
        }
        return array_sum($dayscount);
    }

    function getDatesFromRange($start, $end,$leaveCategory,$leaveType, $format = 'Y-m-d')
    {
        // Declare an empty array
        $array = array();
        
        // Variable that store the date interval
        // of period 1 day
        $interval = new DateInterval('P1D');  
        $realEnd = new DateTime($end);
        $realEnd->add($interval);
        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);
        $weekEnd = array("Saturday","Sunday");
       
        // Use loop to store date into array
        foreach($period as $date) {      
            if(!in_array( date('l', strtotime($date->format($format))),$weekEnd)){           
            $array[] = array(
                "id"=> 1, 
                'title'=> $leaveCategory.' ' .$leaveType,
                'start'=> $date->format($format), 
                'end'=> $date->format($format),
                'allDay'=> false,
                'editable'=> false,
                'allDay'=> true,
                'rendering'=> 'background',
                'backgroundColor'=> '#014c8c',
                'textColor'=> '#000',
                'className'=> 'leave'
            ); 
        }else{
            $array[] = array(
                "id"=> 1, 
                'title'=> 'weekend', 
                'start'=> $date->format("Y-m-d"), 
                'end'=> $date->format("Y-m-d"),
                'allDay'=> false,
                'editable'=> false,
                'allDay'=> true,
                'rendering'=> 'background',
                'backgroundColor'=> '#abacb3',
                'className'=> ["weekend"] 
            );
        }
        }
        return $array;
    }

    public function calandar_view(){
        $user_id = Auth::user()->id;
        return $this->salarydata($user_id);
    }

    public function user_calandar($user_id)
    {
        return $this->salarydata($user_id);
    }

    public function user_calandar_view($id)
    {
        $empData = DB::select("SELECT * FROM emp_salary
            LEFT JOIN users ON users.id = emp_salary.user_id 
            where emp_salary.id=".$id
        );
        $user_id = $empData[0]->user_id;
        $data = $this->salarydata($user_id);
        $month = $empData[0]->month;
        $year = $empData[0]->year;
        $monthlastDate = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $start_date = $year.'-'.$month.'-01';
        $end_date = $year.'-'.$month.'-'.$monthlastDate;
        $emp_casual_leaves = DB::select("SELECT sum(leave_count) as total_leave FROM emp_leaves where leave_type='casual_leave' and start_date >= '$start_date' and end_date <= '$end_date' and status IN (0,1) and user_id=".$user_id);
        $emp_sick_leaves = DB::select("SELECT sum(leave_count) as total_leave FROM emp_leaves where leave_type='sick_leave' and start_date >= '$start_date' and end_date <= '$end_date' and status IN (0,1) and user_id=".$user_id);
        $emp_lwp_leaves = DB::select("SELECT sum(leave_count) as total_leave FROM emp_leaves where leave_type='lwp' and start_date >= '$start_date' and end_date <= '$end_date' and status IN (0,1) and user_id=".$user_id);

        $total_leave = [
            "emp_casual_leaves"=>$emp_casual_leaves[0]->total_leave ? $emp_casual_leaves[0]->total_leave : 0,
            "emp_sick_leaves"=>$emp_sick_leaves[0]->total_leave ? $emp_sick_leaves[0]->total_leave : 0,
            "emp_lwp_leaves"=>$emp_lwp_leaves[0]->total_leave ? $emp_lwp_leaves[0]->total_leave : 0,
        ];
        return view('salary/calandarview',
            [
                'data'=>$data,
                'empData'=>$empData,
                'total_leave'=>$total_leave
            ]);
    }    

    public function salarydata($user_id)
    {
        $currrentYear= date('Y');
        $currrentMonth= date('m');
        $noOfWorkingDays = $this->noOfWorkingDays($currrentYear, $currrentMonth, array(0, 6));
        $lastDateOfMonth = cal_days_in_month(CAL_GREGORIAN, $currrentMonth, $currrentYear);
        $start_date = $currrentYear ."-". $currrentMonth ."-01";
        $start_date = "2023-08-01";
        $end_date = $currrentYear ."-". $currrentMonth ."-" .$lastDateOfMonth;
        $comapanyHoliday = DB::select("select * from holidays where status='1'");
        $noOfLeave = DB::select("select * from emp_leaves where (leave_type='casual_leave' or leave_type='sick_leave' or leave_type='lwp') and status=1 and user_id=".$user_id);
        $sql = "select * from timesheet where select_date between '$start_date' AND '$end_date' and user_id=".$user_id;
        $timeSheet = DB::select($sql);
        $total_number_leave = array_sum(array_column($noOfLeave,'leave_count'));
        $comapanyHolidayDataArray = [];
        foreach($comapanyHoliday as $comapanyHolidayData){
            $comapanyHolidayDataArray[] = array(
                "id" => 1, 
                'title' => $comapanyHolidayData->holiday_name, 
                'start' => $comapanyHolidayData->date, 
                'end' => $comapanyHolidayData->date,
                'allDay' => false,
                'editable' => false,
                'allDay' => true,
                'rendering' => 'background',
                'backgroundColor' => '#f2ebb0',
                'textColor' => '#000',
                'className' => 'Holiday'
            );
        }

        $allLeaveData = [];
        foreach($noOfLeave as $leaveData){
            $start_date = date("Y-m-d", strtotime($leaveData->start_date));
            $end_date = date("Y-m-d", strtotime($leaveData->end_date));
            $date = $this->getDatesFromRange($start_date, $end_date,$leaveData->leave_type, $leaveData->partical_leave); 
            $allLeaveData[]=$date;
        }
        $timeSheets=[];
        foreach($timeSheet as $timeSheetData){
            $timeSheets[] = array(
                "id"=> 1, 
                'title'=> 'Present', 
                'start'=> $timeSheetData->select_date, 
                'end'=> $timeSheetData->select_date,
                'allDay'=> false,
                'editable'=> false,
                'allDay'=> true,
                'rendering'=> 'background',
                'backgroundColor'=> '#32b678',
                'className'=> ["filledTimeSheet"] 
            );
        }
        
        $allSickCasualLeave = call_user_func_array('array_merge', $allLeaveData);
        $start_date = $currrentYear ."-". $currrentMonth ."-01";
        $end_date = $currrentYear ."-". $currrentMonth ."-" .$lastDateOfMonth;
        $weekend = $this->weekend($start_date,$end_date);
        $fillTimeSheetLeave =array_merge($allSickCasualLeave,$timeSheets);
        $totalList = array_merge($fillTimeSheetLeave,$weekend);
        $totalList = array_merge($totalList,$comapanyHolidayDataArray);
        $totalMonthDateArray = [];
        $lastDateOfMonth = date('d');
        for($start = 01; $start<=$lastDateOfMonth; $start++)
        {
            $totalMonthDateArray [] = $currrentYear."-".$currrentMonth . "-" .str_pad($start, 2, '0', STR_PAD_LEFT);
        }
        
        $totalListWithDateonly = array_column($totalList, 'start');
        $totalAbesent=array_diff($totalMonthDateArray,$totalListWithDateonly);
        $totalAbsentDays = [];
        foreach($totalAbesent as $totalAbesentData){
            $totalAbsentDays[]=array(
                "id"=> 1, 
                'title'=> 'Absent', 
                'start'=> $totalAbesentData, 
                'end'=> $totalAbesentData,
                'allDay'=> false,
                'editable'=> false,
                'allDay'=> true,
                'rendering'=> 'background',
                'backgroundColor'=> '#fd4f4f',
                'className'=> ["absent"] 
            );
        }
        $finializedData = array_merge($totalList,$totalAbsentDays);
        return json_encode($finializedData);
    }

    public function empattendance($user_id)
    {
        $data = $this->salarydata($user_id);
        $emp_casual_leaves = DB::select("SELECT sum(leave_count) as total_leave FROM emp_leaves where leave_type='casual_leave' and status IN (0,1) and user_id=".$user_id);
        $emp_sick_leaves = DB::select("SELECT sum(leave_count) as total_leave FROM emp_leaves where leave_type='sick_leave' and status IN (0,1) and user_id=".$user_id);
        $emp_lwp_leaves = DB::select("SELECT sum(leave_count) as total_leave FROM emp_leaves where leave_type='lwp' and status IN (0,1) and user_id=".$user_id);
        $empName = DB::select("SELECT name FROM users where id=".$user_id);
        $total_leave = [
            "emp_casual_leaves"=>$emp_casual_leaves[0]->total_leave?$emp_casual_leaves[0]->total_leave:0,
            "emp_sick_leaves"=>$emp_sick_leaves[0]->total_leave?$emp_sick_leaves[0]->total_leave:0,
            "emp_lwp_leaves"=>$emp_lwp_leaves[0]->total_leave?$emp_lwp_leaves[0]->total_leave:0,
        ];
        
        return view('employee/empattendance',[
            'data' => $data,
            'empName' => $empName,
            'total_leave' => $total_leave
        ]);
    }

    public function weekend($start,$enddate)
    {
        $begin  = new DateTime($start);
        $end    = new DateTime($enddate);
        $finalArray = [];
        while ($begin <= $end) // Loop will work begin to the end date 
        {
            if($begin->format("D") == "Sun" || $begin->format("D") == "Sat") //Check that the day is Sunday here
            {
                $finalArray[] = array(
                    "id"=> 1, 
                    'title'=> 'weekend', 
                    'start'=> $begin->format("Y-m-d"), 
                    'end'=> $begin->format("Y-m-d"),
                    'allDay'=> false,
                    'editable'=> false,
                    'allDay'=> true,
                    'rendering'=> 'background',
                    'backgroundColor'=> '#abacb3',
                    'className'=> ["weekend"] 
                );
            }
            $begin->modify('+1 day');
        }
        return ($finalArray);
    }

/**
 * @param array $columnNames
 * @param array $rows
 * @param string $fileName
 * @return \Symfony\Component\HttpFoundation\StreamedResponse
 */
    public static function getCsv($columnNames, $rows, $fileName = 'file.csv') {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=" . $fileName,
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];
        $callback = function() use ($columnNames, $rows ) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columnNames);
            foreach ($rows as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };
        return  response()->stream($callback, 200, $headers); 
    }
    public function exportsalaryCSV(Request $request)
    {
        $sql = "select users.employee_code, users.name,emp_salary.*,emp_account_details.bank_name, 
                emp_account_details.acc_no ,emp_account_details.ifsc ,emp_account_details.salary from emp_salary
                left join users on users.id=emp_salary.user_id 
                left join emp_account_details on emp_salary.user_id=emp_account_details.user_id where emp_salary.year=$request->year and emp_salary.month='$request->month'";
        $salaryRecord = DB::select($sql);
      /*  if(!empty($request->selectedIds))
        {
            $salaryRecord = DB::select("select users.employee_code, users.name,emp_salary.*,emp_account_details.bank_name, 
                emp_account_details.acc_no ,emp_account_details.ifsc ,emp_account_details.salary from emp_salary
                left join users on users.id=emp_salary.user_id 
                left join emp_account_details on emp_salary.user_id=emp_account_details.user_id 
                where emp_salary.id IN (".implode(',', $request->selectedIds).")");
        }else{ 
            $salaryRecord = DB::select("select users.employee_code, users.name,emp_salary.*,emp_account_details.bank_name, 
                emp_account_details.acc_no ,emp_account_details.ifsc ,emp_account_details.salary from emp_salary 
                left join users on users.id=emp_salary.user_id
                left join emp_registrations on emp_registrations.user_id=emp_salary.user_id 
                left join emp_account_details on emp_salary.user_id=emp_account_details.user_id
                where emp_registrations.status='1' and emp_salary.year=$request->year and emp_salary.month='$request->month'");
        } */
        $rows = [];
        foreach($salaryRecord as $data)
        {
            $rows[] = array(
                $data->employee_code,
                $data->name,
                $data->bank_name,
                $data->acc_no,
                $data->ifsc,
                $data->credit_salary
                // $data->salary,
                // ($data->salary - $data->credit_salary)
            );
        }
    $columnNames = ['Employee Code', 'Name', 'Bank Name','Account Number','IFSC','Credited Salary'/*, 'Total Salary', 'Deducted Salary'*/];//replace this with your own array of string column headers        
        return self::getCsv($columnNames, $rows);
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function pdfview($id)
    {  
        $salaryData = DB::select("select *, emp_salary.created_at from emp_salary left join users on users.id=emp_salary.user_id 
            left join emp_registrations on emp_registrations.user_id=emp_salary.user_id
            left join emp_account_details on emp_account_details.user_id=emp_salary.user_id
            left join emp_accounts on emp_accounts.user_id=emp_salary.user_id
            where emp_salary.id=".$id  );
          // print_r($salaryData);exit;
        $month = $salaryData[0]->month;
        $year = $salaryData[0]->year;
        $empId = $salaryData[0]->user_id;
        
		if(Auth::user()->id != $empId && !in_array(Auth::user()->role,array(3,5,6))){
			$url= $_SERVER['REQUEST_URI']; 
			return Redirect::to('/dashboard');
			}
        $lastDateOfMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $start_date = $year ."-". $month ."-01";
        $end_date = $year ."-". $month ."-" .$lastDateOfMonth;

         
        $LWPsql =DB::select("SELECT SUM(leave_count) as leave_count FROM emp_leaves where start_date >= '$start_date' and end_date <= '$end_date' and leave_type='lwp' and status=1 and emp_leaves.user_id=".$salaryData[0]->user_id);

        $workedInMonth =DB::select("select DISTINCT(select_date)  from timesheet where select_date between '$start_date' AND '$end_date' and user_id=".$salaryData[0]->user_id);
        $basic_salary = 0;
        $house_rent_allowance = 0;
        $transport_allowance = 0;
        $special_allowance = 0;
        $extra_pay = 0;
        $tds = 0;
        $employeeBand =DB::select("SELECT * FROM emp_band where status='1'");
        
        foreach($employeeBand as $employeeBandData){
            if($salaryData[0]->employee_band == $employeeBandData->emp_band){
                $basic_salary = ((float)$salaryData[0]->credit_salary*(float)$employeeBandData->basic_salary)/100;
                $house_rent_allowance = ((float)$salaryData[0]->credit_salary*(float)$employeeBandData->house_rent_allounce)/100;
                $transport_allowance = ((float)$salaryData[0]->credit_salary*(float)$employeeBandData->transport_allounce)/100;
                $special_allowance = ((float)$salaryData[0]->credit_salary*(float)$employeeBandData->special_allounce)/100;
                $extra_pay = ((float)$salaryData[0]->credit_salary*(float)$employeeBandData->extra_pay)/100;
                $tds = 0;
                
                if($employeeBandData->tds_type=='Yes'){
                    $tds = ((float)$salaryData[0]->credit_salary*$employeeBandData->tds)/100;
                }
                $net_salary = ($basic_salary + $house_rent_allowance + $transport_allowance + $special_allowance + $extra_pay - $tds);
            }
        }
         
        $salarySlipData = array(
            'employee_id'=>$salaryData[0]->employee_code,
            'name'=>$salaryData[0]->name,
            'monthYear'=>  date('F', mktime(0,0,0,$salaryData[0]->month)).' '.$salaryData[0]->year,
            'employee_band'=>$salaryData[0]->employee_band,
            'department'=>$salaryData[0]->job_title,
            'joining_date'=>$salaryData[0]->joining_date,
            'pan_number'=>$salaryData[0]->pan_number,
            'bank_name'=>$salaryData[0]->bank_name,
            'acc_no'=>$salaryData[0]->acc_no,
            'day_worked_in_month'=>count($workedInMonth),
            'LWPA'=>$LWPsql[0]->leave_count?$LWPsql[0]->leave_count:0,
            'basic_salary'=>$basic_salary,
            'house_rent_allowance'=>$house_rent_allowance,
            'transport_allowance'=>$transport_allowance,
            'special_allowance'=>$special_allowance,
            'extra_pay'=>$extra_pay,
            'tds'=>$tds,
            'net_salary'=>$net_salary,
            'net_salary_in_words'=>$this->numberConvertInToWords($net_salary),
            'logo_img'=>url('assets/img/hram_logo_new_1.png')
        );
        
        $pdf = Pdf::loadView('salary/pdfview',['salaryData'=>$salarySlipData])->setPaper('a4', 'portrait');
        
        // Output the generated PDF to Browser
      return  $pdf->stream();
    }

    public function numberConvertInToWords($number){
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'one', '2' => 'two',
            '3' => 'three', '4' => 'four', '5' => 'five', '6' => 'six',
            '7' => 'seven', '8' => 'eight', '9' => 'nine',
            '10' => 'ten', '11' => 'eleven', '12' => 'twelve',
            '13' => 'thirteen', '14' => 'fourteen',
            '15' => 'fifteen', '16' => 'sixteen', '17' => 'seventeen',
            '18' => 'eighteen', '19' =>'nineteen', '20' => 'twenty',
            '30' => 'thirty', '40' => 'forty', '50' => 'fifty',
            '60' => 'sixty', '70' => 'seventy',
            '80' => 'eighty', '90' => 'ninety');
        $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred
                    :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ?
            "." . $words[$point / 10] . " " . 
                $words[$point = $point % 10] : '';
        return strtoupper($result . "Rupees  " . $points. " Paise");
    }
}
