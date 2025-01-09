<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmpBand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EmpBands extends Controller
{
    public function empband_add() 
    {   
        $bandData = DB::select("select * from emp_band where status = '1'");
        
        return view('empband.band',['bandData'=> $bandData])->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function empband_save(Request $request)
    {
        $request->validate([
            'emp_band' => 'required',
            'basic_salary' => 'required|numeric',
            'house_rent_allounce' => 'required|numeric',
            'tds_type' => 'required',
            'transport_allounce' => 'required|numeric',
            'special_allounce' => 'required|numeric',
            'extra_pay' => 'required|numeric'
        ]);

        if($request->tds == NULL){
            $request->tds = 0.00;
        }
        EmpBand::create([
            'emp_band' => $request->emp_band,
            'basic_salary' => $request->basic_salary,
            'house_rent_allounce' => $request->house_rent_allounce,
            'tds_type' => $request->tds_type,
            'transport_allounce' => $request->transport_allounce,
            'special_allounce' => $request->special_allounce,
            'extra_pay' => $request->extra_pay,
            'tds' => $request->tds,
            'status' => '1'
        ]);
        return redirect()->route('empband')->with('success','Employee Band Added Successfully.');
        
    }

    public function empband_edit($id)
    {
        $empband = EmpBand::where('id', $id)->firstOrFail();
       
        return view('empband.bandedit', compact('empband'));
    }

    public function empband_update(Request $request, $id)
    {
        $request->validate([
            'emp_band' => 'required',
            'basic_salary' => 'required|numeric',
            'house_rent_allounce' => 'required|numeric',
            'tds_type' => 'required',
            'transport_allounce' => 'required|numeric',
            'special_allounce' => 'required|numeric',
            'extra_pay' => 'required|numeric'
        ]);

        if($request->tds == NULL){
            $request->tds = 0.00;
        }        
        
        $empBand = EmpBand::where('id', $id)->firstOrFail();
        $empBand->update([
            'emp_band' => $request->emp_band,
            'basic_salary' => $request->basic_salary,
            'house_rent_allounce' => $request->house_rent_allounce,
            'tds_type' => $request->tds_type,
            'transport_allounce' => $request->transport_allounce,
            'special_allounce' => $request->special_allounce,
            'extra_pay' => $request->extra_pay,
            'tds' => $request->tds,
            'status' => $request->status
        ]);
        return redirect()->route('empband')->with('success','Employee Band Updated Successfully');
    }

    public function empband_view($id)
    {
        $timesheet = EmpBand::where('id', $id)->firstOrFail();
        $users = DB::select("SELECT * FROM users");
        $projects = DB::select("SELECT * FROM projects");
        $commentHistory = DB::table('time_sheet_comments')->where('timesheet_id', $id)->orderBy('updated_at', 'desc')->paginate(3);
        return view('timesheet.timesheetview', compact('timesheet', 'projects', 'users','commentHistory'));
    }
}
