<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\EmpSalary;
use App\Http\Controllers\Exception;

class EmpSalaryGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'employee:salary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Employee Salary Generate';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $empSalary = DB::select('select * from emp_salary');
            $currrentYear = date('Y');
            $currrentMonth = date('m');
            $noOfWorkingDays = $this->noOfWorkingDays($currrentYear, $currrentMonth, array(0, 6));
            $lastDateOfMonth = cal_days_in_month(CAL_GREGORIAN, $currrentMonth, $currrentYear);
            if(date('d') == $lastDateOfMonth)
            {
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
                        $user_id = DB::select("SELECT id FROM users WHERE employee_code = '".$dataValue['employee_code']."'");
                        if(!empty($user_id))
                        {
                            $noOfLeave = DB::select("select * from emp_leaves where (leave_type='casual_leave' or leave_type='sick_leave') and status=1 and user_id=".$user_id[0]->id);
                            $lwpLeave = DB::select("select * from emp_leaves where leave_type='lwp' and status=1 and user_id=".$user_id[0]->id);
                            $sql = "select * from timesheet where select_date between '$start_date' AND '$end_date' and user_id=".$user_id[0]->id;
                            $timeSheet = DB::select($sql);
                            $total_number_leave = array_sum(array_column($noOfLeave,'leave_count'));
                            $total_number_leave_lwp = array_sum(array_column($lwpLeave,'leave_count'));
                            $noOfAbsent = $noOfWorkingDays - ($total_number_leave + count($timeSheet) + $this->holiday($start_date,$end_date));
                            echo "noOfWorkingDays".$noOfWorkingDays."<br>";
                            echo "total_number_leave".$total_number_leave."<br>";
                            echo "timeSheet".count($timeSheet)."<br>";
                            echo "holiday".$this->holiday($start_date,$end_date)."<br>";
                            echo "noOfAbsent".$noOfAbsent."<br>";
                            echo "total_number_leave_lwp=".$total_number_leave_lwp."<br>";
                            echo $creditSalary = ($dataValue['salary']) - ((($dataValue['salary'])/$lastDateOfMonth)*$noOfAbsent);
                            $alreadyData = DB::select("SELECT * FROM emp_salary WHERE user_id =". $user_id[0]->id." AND YEAR(CAST(created_at AS DATE))=".$currrentYear." AND MONTH(CAST(created_at AS DATE))='".$currrentMonth."'");
                            
                            if(empty($alreadyData)){
                                EmpSalary::create([
                                    'user_id' => $user_id[0]->id,
                                    'credit_salary' => $creditSalary
                                ]);
                            }else{
                                DB::table('emp_salary')
                                    ->where('id', $alreadyData[0]->id)
                                    ->update(['credit_salary' => $creditSalary]);
                            }
                        }else {
                            continue;
                        }
                    }
                    $counter++;
                }
                fclose($file);
            }
        }catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
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

    public function isWeekend($date) {
        $weekDay = date('w', strtotime($date));
        if($weekDay == 0 || $weekDay == 6){
            return 0;
        }else{
            return 1;
        };
    }
}
