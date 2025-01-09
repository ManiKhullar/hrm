<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeSheet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Helper\HoursMinutes;
use App\Http\Helper\TimesheetMail;

class AdminTimeSheetController extends Controller
{
    public function admintimesheet_add()
    {
        $hours = HoursMinutes::hours();
        $minutes = HoursMinutes::minutes();
        $projects = DB::select("SELECT * FROM projects WHERE status='1'");
        if((Auth::user()->role) == 4)
        {
            $user_id = Auth::user()->id;
            $users = DB::select("SELECT DISTINCT users.id, users.name FROM project_managers 
                LEFT JOIN users on users.id=project_managers.developer_id
                WHERE manager_id = $user_id");
            return view('timesheet.admintimesheet', compact('hours','minutes','users','projects'));
        }
        if((Auth::user()->role) == 5 || (Auth::user()->role) == 6)
        {
            $users = DB::select("select users.id, users.name from users
                left join emp_registrations on users.id=emp_registrations.user_id
                where emp_registrations.status='1'");
            return view('timesheet.admintimesheet', compact('hours','minutes','users','projects'));
        }
    }

    public function admintimesheet_save(Request $request)
    {
        $request->validate([
            'select_emp'=> 'required',
            'select_project'=> 'required',
            'select_date' => 'required',
            'hours' => 'required',
            'minutes' => 'required',
            'description' => 'required',
        ]);
        if((Auth::user()->role) == 4 || (Auth::user()->role) == 5 || (Auth::user()->role) == 6){
            $selectData = date("Y-m-d",strtotime($request->select_date));
            $user_id = $request->select_emp;
            $project_id = $request->select_project;
            $data = DB::select("SELECT * FROM timesheet WHERE user_id=$user_id AND project_id=$project_id AND (status='Pending' OR status='ReferBack') AND select_date='".$selectData."'");
            if(empty($data)){
                $emailTemplate = DB::select("SELECT * FROM email_templates WHERE type = 'fill_time_sheet' AND status = '1'");
                $userName = DB::select("SELECT name FROM users WHERE id = $user_id");
                $managerData = DB::select("SELECT DISTINCT users.email, users.name,projects.project_name FROM users
                LEFT JOIN project_managers ON project_managers.manager_id = users.id
                LEFT JOIN projects ON project_managers.project_id = projects.id
                WHERE project_managers.project_id = $project_id
                AND project_managers.status = '1'");
                $times = $request->hours.":".$request->minutes;

                TimeSheet::create([
                    'project_id' => $project_id,
                    'user_id' => $user_id,
                    'hours' =>  $request->hours,
                    'minutes' =>  $request->minutes,
                    'select_date' =>  $selectData,
                    'description' =>  $request->description,
                    'status' =>  'Pending',
                ]);
                TimesheetMail::timesheetMail($userName[0]->name, $selectData, $times, "Pending", $request->description, $managerData[0], $emailTemplate[0]);
                return redirect()->route('admintimesheetadd')->with('success','Timesheet Created Successfully.');
            } else{
                return redirect()->route('admintimesheetadd')->with('success','Dublicate Entry.');
            }
        }
    }
}
