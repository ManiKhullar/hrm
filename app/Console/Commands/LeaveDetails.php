<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Exception;

class LeaveDetails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Leave Details';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $currrentMonth = date('m');
            $currrentYear = date('Y');
            $lastDateOfMonth = cal_days_in_month(CAL_GREGORIAN, $currrentMonth, $currrentYear);
            $leaveCount = DB::select('select * from emp_registrations');
            foreach($leaveCount as $leaveData)
            {
                $leaveDataforEmp = DB::select("select * from emp_registrations where user_id=".$leaveData->user_id);
                if(date('m')==01 && date('d')==01){
                    if($leaveDataforEmp[0]->casual_leave<=6){
                        $getCasualLeave = $leaveDataforEmp[0]->casual_leave;
                        $getSlickLeave = 0;
                    }

                    if($leaveDataforEmp[0]->casual_leave>6){
                        $getCasualLeave = 6;
                        $getSlickLeave = 0;
                    }
                    
                    $getSlickLeave = 0;
                    $update = DB::table('emp_registrations')
                        ->where('user_id', $leaveData->user_id)
                        ->update(array('casual_leave' => $getCasualLeave, 'sick_leave' => $getSlickLeave));
                }

               if(date('d') == $lastDateOfMonth){
                    $casualLeave = 1;
                    $sickLeave = .5;
                    $getCasualLeave = $leaveDataforEmp[0]->casual_leave + $casualLeave;
                    $getSlickLeave = $leaveDataforEmp[0]->sick_leave + $sickLeave;
                    $update = DB::table('emp_registrations')
                        ->where('user_id', $leaveData->user_id)
                        ->update(array('casual_leave' => $getCasualLeave, 'sick_leave' => $getSlickLeave));
               }
            }
        }catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
    }
}
