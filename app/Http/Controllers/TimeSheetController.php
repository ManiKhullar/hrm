<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeSheet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Helper\HoursMinutes;
use App\Http\Helper\SendMail;
use App\Http\Helper\TimesheetMail;
use DateTime;

class TimeSheetController extends Controller
{
    public function timesheet_add() 
    {   
        $hours = HoursMinutes::hours();
        $minutes = HoursMinutes::minutes();
        $user_id = Auth::user()->id;
        $cDate = date("Y-m-d");
        $fromDate = date("Y-m-d", strtotime("$cDate -31 days"));
        $timesheets = DB::table('timesheet')
            ->leftjoin('projects', 'timesheet.project_id', '=', 'projects.id')
            ->where('timesheet.user_id', $user_id)
            ->whereBetween('timesheet.select_date', array($fromDate, $cDate))
            ->orderBy('timesheet.select_date', 'DESC')
            ->paginate(10,array('timesheet.id','projects.project_name as project_name','timesheet.hours','timesheet.minutes','timesheet.select_date','timesheet.description','timesheet.status'));
        $users = DB::select("SELECT * FROM users WHERE id = $user_id AND role = 4");
        if(empty($users)){
            $projects = DB::select("SELECT DISTINCT projects.id, projects.project_name, project_managers.developer_id
            FROM projects
            LEFT JOIN project_managers
            ON project_managers.project_id = projects.id
            WHERE project_managers.developer_id = $user_id
            AND projects.status = '1'
            AND project_managers.status = '1'");
            return view('timesheet.timesheet',[
                'projects'=> $projects,
                'timesheets'=> $timesheets,
                'hours'=> $hours,
                'minutes'=> $minutes
                ])->with('i', (request()->input('page', 1) - 1) * 10);
        } else{
            $projects = DB::select("SELECT DISTINCT projects.id, projects.project_name, project_managers.manager_id
            FROM projects
            LEFT JOIN project_managers
            ON project_managers.project_id = projects.id
            WHERE (project_managers.manager_id = $user_id || project_managers.developer_id = $user_id)
            AND projects.status = '1'
            AND project_managers.status = '1'");
            return view('timesheet.timesheet',[
                'projects'=> $projects,
                'timesheets'=> $timesheets,
                'hours'=> $hours,
                'minutes'=> $minutes
                ])->with('i', (request()->input('page', 1) - 1) * 10);
        }
    }

    public function timesheet_save(Request $request)
    {
        $request->validate([
            'select_date' => 'required',
            'hours' => 'required',
            'minutes' => 'required',
            'description' => 'required',
        ]);

        $selectData = date("Y-m-d",strtotime($request->select_date));
        $reportMonth = date("m",strtotime($request->select_date));
        $reportYear = date("Y",strtotime($request->select_date));
        $timesheetWindowClose = mktime(0,0,0,date('m')+1,5,date('Y'));
        $currentMonth = mktime(0,0,0,date('m'),1,date('Y'));
        $reportedTime = strtotime($request->select_date);
        $timesheetWindowClose."==>".$reportedTime."==>".$currentMonth;
        if($timesheetWindowClose <= time()){ 
            $request->session()->flash('alert-danger', 'Invalide date!');
            echo "0";
        }
       
        $user_id = $request->user()->id;
        $existLeave = DB::select("SELECT * FROM emp_leaves WHERE user_id = $user_id AND start_date <= '$selectData' AND end_date >= '$selectData' AND status in ('0','1')");
        $data = DB::select("SELECT * FROM timesheet WHERE user_id=$user_id AND project_id=$request->project_id AND (status='Pending' OR status='ReferBack') AND select_date='".$selectData."'");
        if(empty($data) && empty($existLeave)){
            $emailTemplate = DB::select("SELECT * FROM email_templates WHERE type = 'fill_time_sheet' AND status = '1'");
            $userName = DB::select("SELECT name FROM users WHERE id = $user_id");
            $managerData = DB::select("SELECT DISTINCT users.email, users.name, projects.project_name FROM users
            LEFT JOIN project_managers ON project_managers.manager_id = users.id
            LEFT JOIN projects ON project_managers.project_id = projects.id
            WHERE project_managers.project_id = $request->project_id
            AND project_managers.status = '1'");
            $times = $request->hours.":".$request->minutes;

            TimeSheet::create([
                'project_id' => $request->project_id,
                'user_id' => $user_id,
                'hours' =>  $request->hours,
                'minutes' =>  $request->minutes,
                'select_date' =>  $selectData,
                'description' =>  $request->description,
                'status' =>  'Pending',
            ]);
            TimesheetMail::timesheetMail($userName[0]->name, $selectData, $times, "Pending", $request->description, $managerData[0], $emailTemplate[0]);
            //return redirect()->route('timesheet')->with('success','Timesheet Created Successfully.');
            echo "1";
        }else{
            $request->session()->flash('alert-danger', 'Duplicate entry or you have already applied for leave!');
           // return redirect()->route('timesheet');
            echo "2";
        } 
        exit;
    }

    public function timesheet_edit($id)
    {
        $timesheet = Timesheet::where('id', $id)->firstOrFail();
        $hours = HoursMinutes::hours();
        $minutes = HoursMinutes::minutes();
        return view('timesheet.timesheetedit', compact('timesheet','hours','minutes'));
    }

    public function timesheet_update(Request $request, $id)
    {
        $request->validate([
            'hours' => 'required',
            'minutes' => 'required',
            'description' => 'required',
        ]);
        
        $timesheet = Timesheet::where('id', $id)->firstOrFail();
        $timesheet->update([
            'hours' =>  $request->hours,
            'minutes' =>  $request->minutes,
            'description' =>  $request->description,
            'status' =>  'Pending',
        ]);
        return redirect()->route('timesheet')->with('success','Timesheet Updated Successfully');
    }

    public function timesheet_view($id)
    {
        $timesheet = Timesheet::where('id', $id)->firstOrFail();
        $users = DB::select("SELECT * FROM users");
        $projects = DB::select("SELECT * FROM projects");
        $commentHistory = DB::table('time_sheet_comments')->where('timesheet_id', $id)->orderBy('updated_at', 'desc')->paginate(3);
        return view('timesheet.timesheetview', compact('timesheet', 'projects', 'users','commentHistory'));
    }

    public function timesheet_filter(Request $request)
    {
        $project_id = $request->project_name;
        $user_id = Auth::user()->id;
        $status = $request->status;
        $from = date('Y-m-d',strtotime($request->from));
        $to = date('Y-m-d',strtotime($request->to));
        $query = '';
        if($project_id){
            $query .= ' AND timesheet.project_id=' . $project_id;
        }
        if($status){
            $query .= ' AND timesheet.status="' . $status.'"';
        }
        if($from && $to){
            $query .= " AND timesheet.select_date between '" . $from . "' and '" . $to ."'";
        }
        
        $hours = HoursMinutes::hours();
        $minutes = HoursMinutes::minutes();
        $timesheets = DB::select("SELECT timesheet.id, projects.project_name, timesheet.hours, timesheet.minutes, timesheet.select_date, timesheet.description, timesheet.status FROM timesheet 
            LEFT JOIN projects ON projects.id = timesheet.project_id
            WHERE timesheet.user_id = $user_id "." "."$query");
        
        if((Auth::user()->role) == 2){
            $projects = DB::select("SELECT DISTINCT projects.id, projects.project_name, project_managers.developer_id
                FROM projects
                LEFT JOIN project_managers ON project_managers.project_id = projects.id
                WHERE project_managers.developer_id = $user_id
                AND projects.status = '1'
                AND project_managers.status = '1'");
        }
        if((Auth::user()->role) == 4){
            $projects = DB::select("SELECT DISTINCT projects.id, projects.project_name, project_managers.manager_id
                FROM projects
                LEFT JOIN project_managers ON project_managers.project_id = projects.id
                WHERE project_managers.manager_id = $user_id
                AND projects.status = '1'
                AND project_managers.status = '1'");
        }
        
        return view('timesheet.timesheetfilter',[
            'projects'=> $projects,
            'timesheets'=> $timesheets,
            'hours'=> $hours,
            'minutes'=> $minutes
            ])->with('i', (request()->input('page', 1) - 1) * 5);
    }
}
