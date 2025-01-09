<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Exception;

class LeaveApprove extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'leave:approve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Leave Approves';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
           
            $startDeate = date('Y-m-d', strtotime('first day of previous month'));
           
            $endDate =  date('Y-m-d', strtotime('last day of previous month'));
          if(date('d')==01){
           //$sql = "update emp_leaves set status=1,approved_by=477 where el.status =0 AND  (el.start_date between '".$startDeate."' and '".$endDate."') OR (el.end_date between '".$startDeate."' and '".$endDate."')";
           DB::table('emp_leaves')
            ->where('status', 0)
            ->where(function ($query) use ($startDeate, $endDate) {
                $query->whereBetween('start_date', [$startDeate, $endDate])
                    ->orWhereBetween('end_date', [$startDeate, $endDate]);
            })
            ->update(['status' => 1, 'approved_by' => 477]);
}
        }catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
    }
}
