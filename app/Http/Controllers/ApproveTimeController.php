<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeSheet;
use App\Models\TimeSheetComments;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Helper\SendMail;

class ApproveTimeController extends Controller
{
    public function approvetime_add(){
        $user_id = Auth::user()->id;
        $projects = DB::select("SELECT * FROM projects");
        $project_managers = DB::select("SELECT DISTINCT project_id FROM project_managers WHERE manager_id = $user_id");
        $developers = DB::select("SELECT DISTINCT developer_id FROM project_managers WHERE manager_id = $user_id");
        $userData =  DB::select("select users.id, users.name from users
            left join emp_registrations on users.id=emp_registrations.user_id
            where emp_registrations.status='1'");

        if((Auth::user()->role) == 4)
        {
            $timesheets = DB::table("timesheet")
                ->leftjoin("users", "timesheet.user_id", '=', "users.id")
                ->leftjoin("emp_registrations", "timesheet.user_id", '=', "emp_registrations.user_id")
                ->leftjoin("projects", "projects.id", '=', "timesheet.project_id")
                ->leftjoin("project_managers", "project_managers.project_id", '=', "timesheet.project_id")
                ->where([
                    ['project_managers.manager_id',$user_id],
                    ['project_managers.status','1'],
                    ['emp_registrations.status','1']
                ])
                ->WhereBetween('users.role',['2','3'])
                ->groupBy('timesheet.id')
                ->orderBy('timesheet.select_date','DESC')
                ->paginate(30,array('users.name','projects.project_name','timesheet.id','timesheet.hours','timesheet.minutes','timesheet.select_date','timesheet.description','timesheet.status'));

            return view('timesheet.approvetime',[
                'timesheets'=> $timesheets,
                'projects'=> $projects,
                'project_managers'=> $project_managers,
                'developers'=> $developers,
                'userData'=> $userData
                ])->with('i', (request()->input('page', 1) - 1) * 5);
        }
        if((Auth::user()->role) == 5 || (Auth::user()->role) == 6){
            $cDate = date("Y-m-d");
            $fromDate = date("Y-m-d", strtotime("$cDate -31 days"));
            $timesheetData = DB::table("timesheet")
                ->leftjoin("users", "timesheet.user_id", '=', "users.id")
                ->leftjoin("emp_registrations", "timesheet.user_id", '=', "emp_registrations.user_id")
                ->leftjoin("projects", "projects.id", '=', "timesheet.project_id")
                ->where('emp_registrations.status','1')
                ->whereBetween('timesheet.select_date', array($fromDate, $cDate))
                ->orderBy('timesheet.select_date', 'DESC')
                ->paginate(30,array('timesheet.id','projects.project_name','users.name','timesheet.hours','timesheet.minutes','timesheet.select_date','timesheet.description','timesheet.status'));
            
            return view('timesheet.approvesupertimelist',[
                'timesheetData'=> $timesheetData,
                'userData' => $userData
            ]);
        }
    }

    public function approvetime_edit($id)
    {
        $projects = DB::select("SELECT * FROM projects");
        $commentHistory = DB::table('time_sheet_comments')->where('timesheet_id', $id)->orderBy('updated_at', 'desc')->paginate(3);
        $users = DB::select("SELECT * FROM users");
        $timesheet = Timesheet::where('id', $id)->firstOrFail();
        return view('timesheet.approvetimeedit', [
            'timesheet'=> $timesheet,
            'projects'=> $projects,
            'commentHistory'=> $commentHistory,
            'users'=> $users
            ])->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function approvetime_update(Request $request, $id)
    {
        $manager_id = $request->user()->id;
        $request->validate([
            'status' => 'required',
        ]);
        TimeSheetComments::create([
            'timesheet_id' => $id,
            'status' => $request->status,
            'comment_history' => $request->manager_comment
        ]);
        $userData = DB::select("SELECT projects.project_name, users.email, users.name, timesheet.select_date, timesheet.description, timesheet.hours, timesheet.minutes FROM timesheet
            LEFT JOIN users ON timesheet.user_id = users.id
            LEFT JOIN projects ON timesheet.project_id = projects.id
            WHERE timesheet.id = $id");
        
        $from = 'noreply@mybluethink.in';
        $to = $userData[0]->email;
        $times = $userData[0]->hours .'h '. $userData[0]->minutes;

        $manager = DB::select("SELECT name FROM users WHERE id = $manager_id");
        $emailTemplate = DB::select("SELECT * FROM email_templates WHERE type = 'approve_time_sheet' AND status = '1'");
        
        $subject = str_replace("{{##STATUS$}}",$request->status,$emailTemplate[0]->subject);
        $name = str_replace("{{##USERNAME$}}",$userData[0]->name,$emailTemplate[0]->content);
        $status = str_replace("{{##STATUS$}}",$request->status,$name);
        $managerName = str_replace("{{##MANAGERNAME$}}",$manager[0]->name,$status);
        $contentData = "<tr style='background-color: #f7f7f7;'>
                            <td align='center' valign='top' style='padding-top: 8px; padding-bottom: 10px;' >
                            <table style='border: 1px solid #eee;' border='0' cellpadding='10' cellspacing='0' width='100%' id='emailBody'>
                                <tr style='text-align: left;'>
                                    <th style='border: 1px solid #ccc;'>Project Name</th>
                                    <th style='border: 1px solid #ccc;'>Work Date</th>
                                    <th style='border: 1px solid #ccc;'>Hours</th>
                                    <th style='border: 1px solid #ccc;'>Status</th>
                                </tr>
                                <tr>
                                    <td style='border: 1px solid #ccc;'>".$userData[0]->project_name."</td>
                                    <td style='border: 1px solid #ccc;'>".$userData[0]->select_date."</td>
                                    <td style='border: 1px solid #ccc;'>".$times."</td>
                                    <td style='border: 1px solid #ccc;'>".$request->status."</td>
                                </tr>
                        
                            </table>
                            <p><b>Description:</b> <span>".$userData[0]->description."</span></p>
                            <p><b>Manager Comment:</b> <span>".$request->manager_comment."</span></p>
                            </td>
                        </tr>";

        $html = str_replace("{{##MASSAPPROVE$}}",$contentData,$managerName);
        $update = DB::table('timesheet')
            ->where('id', $id)
            ->update(array(
                'status' => $request->status,
                'manager_id' => $manager_id,
                'manager_comment' => $request->manager_comment
            ));
        SendMail::sendMail($html, $subject, $to, $from, '');
        return redirect()->route('approvetime')->with('success','Timesheet Status Changed Successfully');
    }

    public function superadmintimesheet_filter(Request $request)
    {
        $from = date('Y-m-d', strtotime($request->from));
        $to = date('Y-m-d', strtotime($request->to));

        if(!empty($request->emp_id))
        {
            $timesheetData = DB::table("timesheet")
                ->leftjoin("users", "timesheet.user_id", '=', "users.id")
                ->leftjoin("emp_registrations", "timesheet.user_id", '=', "emp_registrations.user_id")
                ->leftjoin("projects", "projects.id", '=', "timesheet.project_id")
                ->where('users.id','=',$request->emp_id)
                ->where('emp_registrations.status','1')
                ->WhereBetween('timesheet.select_date',[$from, $to])
                ->paginate(30,array('timesheet.id','projects.project_name','users.name','timesheet.hours','timesheet.minutes','timesheet.select_date','timesheet.description','timesheet.status'));
        }else{
            $timesheetData = DB::table("timesheet")
                ->leftjoin("users", "timesheet.user_id", '=', "users.id")
                ->leftjoin("emp_registrations", "timesheet.user_id", '=', "emp_registrations.user_id")
                ->leftjoin("projects", "projects.id", '=', "timesheet.project_id")
                ->where('emp_registrations.status','1')
                ->WhereBetween('timesheet.select_date',[$from, $to])
                ->paginate(30,array('timesheet.id','projects.project_name','users.name','timesheet.hours','timesheet.minutes','timesheet.select_date','timesheet.description','timesheet.status'));
        }
        
        if(!empty($request->emp_id)){
            $timesheetData->appends(['emp_id'=>$request->emp_id]);
        }
        if(!empty($request->from) && !empty($request->to)){
            $timesheetData->appends(['from'=>$request->from]);
            $timesheetData->appends(['to'=>$request->to]);
        }
        $userData =  DB::select("select users.id, users.name from users
            left join emp_registrations on users.id=emp_registrations.user_id
            where emp_registrations.status='1'");
        return view('timesheet.approvesupertimelist',[
            'timesheetData'=> $timesheetData,
            'userData' => $userData
        ]);
    }

    public function approvetime_filter(Request $request)
    {
        $project_id = $request->project_name;
        $user_id = $request->user_id;
        $from = date('Y-m-d',strtotime($request->from));
        $to = date('Y-m-d',strtotime($request->to));

        $current_id = Auth::user()->id;
        $projects = DB::select("SELECT * FROM projects");
        $project_managers = DB::select("SELECT DISTINCT project_id FROM project_managers WHERE manager_id = $current_id");
        $developers = DB::select("SELECT DISTINCT developer_id FROM project_managers WHERE manager_id = $current_id");
        $userData =  DB::select("select users.id, users.name from users
            left join emp_registrations on users.id=emp_registrations.user_id
            where emp_registrations.status='1'");

        if((Auth::user()->role) == 4)
        {
            $timesheets = DB::table("timesheet")
                ->leftjoin("users", "timesheet.user_id", '=', "users.id")
                ->leftjoin("projects", "projects.id", '=', "timesheet.project_id")
                ->leftjoin("emp_registrations", "timesheet.user_id", '=', "emp_registrations.user_id")
                ->leftjoin("project_managers", "project_managers.project_id", '=', "timesheet.project_id")
                ->select('users.name','projects.project_name','timesheet.id','timesheet.hours','timesheet.minutes','timesheet.select_date','timesheet.description','timesheet.status')
                ->Where('project_managers.manager_id',$current_id)
                ->Where('project_managers.status','1')
                ->Where('emp_registrations.status','1')
                ->WhereBetween('users.role',['2','3']);
                if($request->project_name!=''){
                    $timesheets = $timesheets->Where('timesheet.project_id',$project_id);
                }
                if($request->user_id!=''){
                    $timesheets = $timesheets->Where('timesheet.user_id',$user_id);
                }
                $timesheets= $timesheets->WhereBetween('timesheet.select_date', array($from, $to))
                ->groupBy('timesheet.id')
                ->orderBy('timesheet.select_date','DESC')
                ->paginate(10,array('users.name','projects.project_name','timesheet.id','timesheet.hours','timesheet.minutes','timesheet.select_date','timesheet.description','timesheet.status'));
            
            if($request->project_name){
                $timesheets->appends(['project_name'=>$request->project_name]);
            }
            if($request->user_id){
                $timesheets->appends(['user_id'=>$request->user_id]);
            }
            if(!empty($from) && !empty($to)){
                $timesheets->appends(['from'=>$request->from]);
                $timesheets->appends(['to'=>$request->to]);
            }
           
            return view('timesheet.approvetime',[
                'timesheets'=> $timesheets,
                'projects'=> $projects,
                'project_managers'=> $project_managers,
                'developers'=> $developers,
                'userData'=> $userData
                ])->with('i', (request()->input('page', 1) - 1) * 5);
        }
    }

    public function approvetime_mass(Request $request)
    {   
        $contentData = "";
        $manager_id = $request->user()->id;

        foreach ($request->selectedIds as $id) {
            $userData = DB::select("SELECT projects.project_name, users.email, users.name, timesheet.select_date, timesheet.description, timesheet.hours, timesheet.minutes FROM timesheet
                LEFT JOIN users ON timesheet.user_id = users.id
                LEFT JOIN projects ON timesheet.project_id = projects.id
                WHERE timesheet.id = $id");

            $from = 'noreply@mybluethink.in';
            $to = $userData[0]->email;
            $times = $userData[0]->hours .'h '. $userData[0]->minutes;

            $manager = DB::select("SELECT name FROM users WHERE id = $manager_id");
            $emailTemplate = DB::select("SELECT * FROM email_templates WHERE type = 'approve_time_sheet' AND status = '1'");
            
            $subject = str_replace("{{##STATUS$}}",$request->status,$emailTemplate[0]->subject);
            $name = str_replace("{{##USERNAME$}}",$userData[0]->name,$emailTemplate[0]->content);
            $status = str_replace("{{##STATUS$}}",$request->status,$name);
            $managerName = str_replace("{{##MANAGERNAME$}}",$manager[0]->name,$status);
            $contentData .= "<tr style='background-color: #f7f7f7;'>
                                <td align='center' valign='top' style='padding-top: 8px; padding-bottom: 10px;' >
                                <table style='border: 1px solid #eee;' border='0' cellpadding='10' cellspacing='0' width='100%' id='emailBody'>
                                    <tr style='text-align: left;'>
                                        <th style='border: 1px solid #ccc;'>Project Name</th>
                                        <th style='border: 1px solid #ccc;'>Work Date</th>
                                        <th style='border: 1px solid #ccc;'>Hours</th>
                                        <th style='border: 1px solid #ccc;'>Status</th>
                                    </tr>
                                    <tr>
                                        <td style='border: 1px solid #ccc;'>".$userData[0]->project_name."</td>
                                        <td style='border: 1px solid #ccc;'>".$userData[0]->select_date."</td>
                                        <td style='border: 1px solid #ccc;'>".$times."</td>
                                        <td style='border: 1px solid #ccc;'>".$request->status."</td>
                                    </tr>
                            
                                </table>
                                <p><b>Description:</b> <span>".$userData[0]->description."</span></p>
                                <p><b>Manager Comment:</b> <span>".$request->manager_comment."</span></p>
                                </td>
                            </tr>";

            $update = DB::table('timesheet')
                ->where('id', $id)
                ->update(array(
                    'status' => $request->status,
                    'manager_id' => $manager_id,
                    'manager_comment' => $request->manager_comment
                ));
        }
        $html = str_replace("{{##MASSAPPROVE$}}",$contentData,$managerName);
        SendMail::sendMail($html, $subject, $to, $from, '');
    }

    public static function getCsv($columnNames, $rows, $fileName = 'timesheet.csv')
    {
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

     return response()->stream($callback, 200, $headers);   
    }

    public function approvetimemass_export(Request $request)
    {
        if(!empty($request->emp_id)){
            $from = date('Y-m-d', strtotime($request->from));
            $to = date('Y-m-d', strtotime($request->to));
            if(!empty($request->selectedIds)){
                $timesheetdata = DB::select("SELECT users.name, projects.project_name, timesheet.hours, timesheet.minutes, timesheet.select_date, timesheet.description, timesheet.status, timesheet.manager_comment FROM timesheet
                    LEFT JOIN users ON users.id = timesheet.user_id
                    LEFT JOIN projects ON projects.id = timesheet.project_id
                    WHERE timesheet.id IN (".implode(',', $request->selectedIds).")". " AND users.id = '" .$request->emp_id . "' and timesheet.select_date BETWEEN '" . $from . "' AND  '" . $to . "'");

            }else{
                $timesheetdata = DB::select("SELECT users.name, projects.project_name, timesheet.hours, timesheet.minutes, timesheet.select_date, timesheet.description, timesheet.status, timesheet.manager_comment FROM timesheet
                    LEFT JOIN users ON users.id = timesheet.user_id
                    LEFT JOIN projects ON projects.id = timesheet.project_id
                    WHERE users.id = '" .$request->emp_id . "' and timesheet.select_date BETWEEN '" . $from . "' AND  '" . $to . "'");
            }
        }else{
            if(!empty($request->selectedIds)){
                $timesheetdata = DB::select("SELECT users.name, projects.project_name, timesheet.hours, timesheet.minutes, timesheet.select_date, timesheet.description, timesheet.status, timesheet.manager_comment FROM timesheet
                    LEFT JOIN users ON users.id = timesheet.user_id
                    LEFT JOIN projects ON projects.id = timesheet.project_id
                    WHERE timesheet.id IN (".implode(',', $request->selectedIds).")");
            }else{
                $timesheetdata = DB::select("SELECT users.name, projects.project_name, timesheet.hours, timesheet.minutes, timesheet.select_date, timesheet.description, timesheet.status, timesheet.manager_comment FROM timesheet
                    LEFT JOIN users ON users.id = timesheet.user_id
                    LEFT JOIN projects ON projects.id = timesheet.project_id");
            }
        }

        $rows = [];
        foreach($timesheetdata as $data){
            $rows[] = array(
                $data->name,
                $data->project_name,
                ($data->hours.'h '.$data->minutes.'m'),
                date('d/m/Y',strtotime($data->select_date)),
                strip_tags($data->description),
                $data->status,
                $data->manager_comment,
            );
        }
        $columnNames = ['Employee Name', 'Project Name', 'Working Times','Working Date','Description','Status', 'Manager Comment'];//replace this with your own array of string column headers        
        return self::getCsv($columnNames, $rows);       
    }
}
