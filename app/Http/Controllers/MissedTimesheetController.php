<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TimeSheet;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use DateTime;

class MissedTimesheetController extends Controller
{
    public function missedtimesheet(Request $request)
    {
        $timesheetData = DB::select("SELECT t1.select_date,u.employee_code,u.name,u.email,u.id FROM timesheet t1 JOIN
            ( SELECT user_id, MAX(select_date) AS last_entry_date FROM timesheet GROUP BY user_id ) 
            t2 ON t1.user_id = t2.user_id AND t1.select_date = t2.last_entry_date inner join 
            users as u on u.id=t1.user_id inner join emp_registrations as e on e.user_id = u.id
            where e.status ='1' and u.timesheet_skip<=0 group by u.id ORDER BY t1.select_date ASC");
       
        return view('missedtimesheet.missedtimesheet',[
            'timesheetData'=> $timesheetData,
            'requet_date' => $request->from
        ]);
    }

    public function missedtimesheet_filter(Request $request)
    {
        $date = date('Y-m-d',strtotime($request->from));
        $sql = "SELECT u.email, u.name,u.employee_code FROM users u  left join emp_registrations as e on e.user_id = u.id LEFT JOIN timesheet t ON e.user_id = t.user_id AND t.select_date = '".$date."' where t.user_id IS NULL and u.timesheet_skip <= 0 and e.status ='1' Order by u.name ASC";
        $timesheetData = DB::select($sql);
        
        return view('missedtimesheet.missedtimesheet',[
            'timesheetData'=> $timesheetData,
            'requet_date' => $request->from
        ]);
    }
}   