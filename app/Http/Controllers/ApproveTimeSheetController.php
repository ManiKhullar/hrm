<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeSheet;
use App\Models\TimeSheetComments;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Helper\SendMail;
use DateTime;

class ApproveTimeSheetController extends Controller
{
    public function approvetime_sheet()
    {
       
        $user_id = Auth::user()->id;
        
        if((Auth::user()->role) == 4)
        {
            $userData =  DB::select("select DISTINCT users.id, users.name from users inner join emp_registrations 
                as er on er.user_id = users.id left join project_managers on users.id= project_managers.developer_id 
                where project_managers.status='1' and er.status ='1' and project_managers.manager_id=$user_id");

            $timesheetData = [];
            $weeklist = $this->weeklist();
           
            $weeklists = [];
            foreach ($weeklist as $key => $value) {
                if(isset($value['start']) && isset($value['end'])):
                $weeklists[] = array(
                    'startvalue' => $value['start'],
                    'endvalue' => $value['end'],
                    'lable' => ($key+1).' Week ('.date("dM",strtotime($value['start'])).' - '.date("dM",strtotime($value['end'])).')'

                );
            endif;
            }

            return view('approvetimesheet.approvetimesheet',[
                'timesheetData'=> $timesheetData,
                'userData' => $userData,
                'weeklists' => $weeklists
                ])->with('i', (request()->input('page', 1) - 1) * 5);
        }
        if((Auth::user()->role) == 7)
        {
            $userData =  DB::select("select DISTINCT users.id, users.name from users inner join emp_registrations 
                as er on er.user_id = users.id left join team_lead on users.id=team_lead.user_id
                where team_lead.status='1' and team_lead.teamlead_id=$user_id");

            $timesheetData = [];
            $weeklist = $this->weeklist();
            $weeklists = [];
            foreach ($weeklist as $key => $value) {
                if(isset($value['start']) && isset($value['end'])):
                $weeklists[] = array(
                    'startvalue' => $value['start'],
                    'endvalue' => $value['end'],
                    'lable' => ($key+1).' Week ('.date("dM",strtotime($value['start'])).' - '.date("dM",strtotime($value['end'])).')'

                );
            endif;
            }

            return view('approvetimesheet.approvetimesheet',[
                'timesheetData'=> $timesheetData,
                'userData' => $userData,
                'weeklists' => $weeklists
                ])->with('i', (request()->input('page', 1) - 1) * 5);
        }
        if((Auth::user()->role) == 5 || (Auth::user()->role) == 6)
        {
            $userData =  DB::select("select users.id, users.name from users inner join emp_registrations 
                as er on er.user_id = users.id left join emp_registrations on users.id=emp_registrations.user_id
            where emp_registrations.status='1'");

            $weeklist = $this->weeklist();
            $weeklists = [];
            foreach ($weeklist as $key => $value) {
                if(isset($value['start']) && isset($value['end'])):
                $weeklists[] = array(
                    'startvalue' => $value['start'],
                    'endvalue' => $value['end'],
                    'lable' => ($key+1).' Week ('.date("dM",strtotime($value['start'])).' - '.date("dM",strtotime($value['end'])).')'

                );
            endif;
            }
            $timesheetData = [];

            return view('approvetimesheet.approvetimesheet',[
                'timesheetData'=> $timesheetData,
                'userData' => $userData,
                'weeklists' => $weeklists
            ]);
        }
    }

    public function weeklist()
    {
        $month = date('m');
        $year = date('Y');
        $date = new DateTime($year.'-'.$month.'-01');

        $count = 1;
        $week[$count]['start'] =  $date->format('Y-m-d');

        while (1) {
            $date->modify('+1 day');
            if ($date->format('m') != $month) {
                $week[$count]['end'] = date('Y-m-d', strtotime($date->format('Y-m-d') . ' - 1 days')); 
                break;
            }

            if ($date->format('D') === 'Sat') {
                $week[$count++]['end'] = $date->format('Y-m-d');
            }

            if ($date->format('D') === 'Sun') {
                $week[$count]['start'] = $date->format('Y-m-d');
            }

        }
        $weekData = array_merge($week,$this->lastMonthWeeklist());
        return $weekData;
    }

    public function lastMonthWeeklist()
    {
        $month = date('m',strtotime("-1 month"));
        $year = date('Y');
        $date = new DateTime($year.'-'.$month.'-01');

        $count = 1;
        $week[$count]['start'] =  $date->format('Y-m-d');

        while (1) {
            $date->modify('+1 day');
            if ($date->format('m') != $month) {
                $week[$count]['end'] = date('Y-m-d', strtotime($date->format('Y-m-d') . ' - 1 days')); 
                break;
            }

            if ($date->format('D') === 'Sat') {
                $week[$count++]['end'] = $date->format('Y-m-d');
            }

            if ($date->format('D') === 'Sun') {
                $week[$count]['start'] = $date->format('Y-m-d');
            }

        }
        return $week;
    }

    public function approvetimesheet_filter(Request $request)
    {
       
        $request->validate([
            'emp_id' => 'required',
            'week_list' => 'required'
        ]);

       

        $userData = [];
        $user_id = Auth::user()->id;
        $managersData = DB::select("select project_id,manager_id from project_managers where status ='1'");
        $managersArray = [];
        foreach($managersData as $data){
            $managersArray[$data->project_id] = $data->manager_id;
        }
        if((Auth::user()->role) == 4)
        {
            $userData =  DB::select("select DISTINCT users.id, users.name from users
                left join project_managers on users.id=project_managers.developer_id
                where project_managers.status='1' and project_managers.manager_id=$user_id");
        }elseif ((Auth::user()->role) == 5 || (Auth::user()->role) == 6) 
        {
            $userData =  DB::select("select users.id, users.name from users
                left join emp_registrations on users.id=emp_registrations.user_id
                where emp_registrations.status='1'");
        }

        $data = explode(',',($request->week_list));
        $timesheetData = DB::table("timesheet")
            ->leftjoin("users", "timesheet.user_id", '=', "users.id")
            ->leftjoin("emp_registrations", "timesheet.user_id", '=', "emp_registrations.user_id")
            ->leftjoin("projects", "projects.id", '=', "timesheet.project_id")
            ->where('users.id','=',$request->emp_id)
            ->where('emp_registrations.status','1')
            ->WhereBetween('timesheet.select_date',[$data[0], $data[1]])
            ->orderBy('select_date', 'DESC')
            ->paginate(31,array('timesheet.id','timesheet.manager_id','timesheet.manager_comment','projects.project_name','users.name','timesheet.project_id','timesheet.hours','timesheet.minutes','timesheet.select_date','timesheet.description','timesheet.status'));
        
        $managerData = DB::select("SELECT id,name FROM users WHERE role in (3,4,5)");
        $authorisedUser = array();
        foreach ($managerData as $managerDatas) {
            $authorisedUser[$managerDatas->id] = $managerDatas->name;
        }
        
        if(!empty($request->emp_id)){
            $timesheetData->appends(['emp_id'=>$request->emp_id]);
        }
        if(!empty($request->week_list)){
            $timesheetData->appends(['week_list'=>$request->week_list]);
        }
        
        $weeklist = $this->weeklist();
        $weeklists = [];
        foreach ($weeklist as $key => $value) {
            if(isset($value['start']) && isset($value['end'])):
            $weeklists[] = array(
                'startvalue' => $value['start'],
                'endvalue' => $value['end'],
                'lable' => ($key+1).' Week ('.date("dM",strtotime($value['start'])).' - '.date("dM",strtotime($value['end'])).')'

            );
        endif;
        }
        if($request->export){
            $rows = [];
            foreach($timesheetData as $tData){
               // print_r($tData); exit;
                $rows[] = array(
                    $tData->name,
                    $tData->project_name,
                    ($tData->hours.'h '.$tData->minutes.'m'),
                    date('d/m/Y',strtotime($tData->select_date)),
                    strip_tags($tData->description),
                    $tData->status,
                    $tData->manager_comment,
                );
            }
            $columnNames = ['Employee Name', 'Project Name', 'Working Times','Working Date','Description','Status', 'Manager Comment'];//replace this with your own array of string column headers        
        return self::getCsv($columnNames, $rows);   
        }

        return view('approvetimesheet.approvetimesheet',[
            'timesheetData'=> $timesheetData,
            'userData' => $userData,
            'manager_data'=>$authorisedUser,
            'weeklists' => $weeklists,
            'managersArray'=>$managersArray
        ]);
    }

    public function approvetimesheet_mass(Request $request)
    {
        $finalArray = array_map(null, $request->selectedIds, $request->managerComment);
        $manager_id = $request->user()->id;
        $contentData = "";

        foreach ($finalArray as $data) {
            $id = $data[0];
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
                                <p><b>Manager Comment:</b> <span>".$data[1]."</span></p>
                                </td>
                            </tr>";

            $update = DB::table('timesheet')
                ->where('id', $id)
                ->update(array(
                    'status' => $request->status,
                    'manager_id' => $manager_id,
                    'manager_comment' => $data[1]
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
}
