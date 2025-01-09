<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FreeDisk extends Controller
{
    public function free_disk(){
        return view('boost.boost');
    }

    public function boost_server(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'from' => 'required',
            'to' => 'required',
        ]);
        $from = date("Y-m-d",strtotime($request->from));
        $to = date("Y-m-d",strtotime($request->to));
        $fromTime = date("Y-m-d H:i:s",strtotime($request->from));
        $toTime = date("Y-m-d H:i:s",strtotime($request->to));

        if((Auth::user()->role) == 5)
        {
            if ($request->category == 'Timesheet') 
            {
                DB::beginTransaction();
                try {
                    DB::table('time_sheet_comments')
                        ->whereIn('timesheet_id', function ($query) use ($from, $to) {
                            $query->select('id')
                                ->from('timesheet')
                                ->whereBetween('select_date', [$from, $to]);
                        })
                        ->delete();
                    DB::table('timesheet')
                        ->whereBetween('select_date', [$from, $to])
                        ->delete();
    
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
                return redirect()->route('freeDisk')->with('success','Timesheet History Deleted Successfully.');
            } elseif ($request->category == 'Leavelist') 
            {
                DB::table('emp_leaves')
                    ->whereBetween('start_date', [$from,$to])
                    ->whereBetween('end_date', [$from,$to])
                    ->delete();
                return redirect()->route('freeDisk')->with('success','Leavelist History Deleted Successfully.');
            } elseif ($request->category == 'Claimlist') 
            {
                DB::beginTransaction();
                try {
                    DB::table('claim_images')
                        ->whereIn('claim_id', function ($query) use ($from, $to) {
                            $query->select('id')
                                ->from('claim')
                                ->whereBetween('start_date', [$from, $to])
                                ->whereBetween('end_date', [$from, $to]);
                        })
                        ->delete();
                    DB::table('claim')
                        ->whereBetween('start_date', [$from, $to])
                        ->whereBetween('end_date', [$from, $to])
                        ->delete();
    
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
                return redirect()->route('freeDisk')->with('success','Claimlist History Deleted Successfully.');
            } elseif ($request->category == 'Salarylist')
            {
                $sql = DB::table('emp_salary')
                    ->whereBetween('created_at', [$fromTime,$toTime])
                    ->delete();
                return redirect()->route('freeDisk')->with('success','Salarylist History Deleted Successfully.');
            } elseif ($request->category == 'Loginlist')
            {
                $sql = DB::table('login_session')
                    ->whereBetween('created_at', [$fromTime,$toTime])
                    ->delete();
                return redirect()->route('freeDisk')->with('success','Loginlist History Deleted Successfully.');
            } else 
            {
                return redirect()->route('freeDisk')->with('error','Invalid Category!');
            }
        } else {
            return redirect()->route('freeDisk')->with('error','Unauthorized Access!');
        }
    }
}
