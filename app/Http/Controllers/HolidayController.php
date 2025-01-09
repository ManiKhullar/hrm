<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Holidays;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class HolidayController extends Controller
{
    public function holiday_add()
    {
        $holidays = DB::table('holidays')
            ->orderBy('date', 'ASC')
            ->paginate(15);
        return view('holidays.holidays',['holidays'=> $holidays])->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function holiday_save(Request $request)
    {
        $request->validate([
            'holiday_name' => 'required',
            'date' => 'required',
            'type' => 'required'
        ]);

        Holidays::create([
            'holiday_name' => $request->holiday_name,
            'date' => date('Y-m-d',strtotime(str_replace('/', '-', $request->date))),
            'status' => $request->status,
            'type' => $request->type
        ]);
        return redirect()->route('holiday')->with('success','Holiday Added Successfully.');          
    }

    public function holiday_edit($id)
    {
        $holidays = Holidays::where('id', $id)->firstOrFail();
        return view('holidays.holidaysedit', compact('holidays'));
    }

    public function holiday_update(Request $request, $id)
    {
        $request->validate([
            'holiday_name' => 'required',
            'date' => 'required',
            'type' => 'required'
        ]);
        $data = date('Y-m-d',strtotime(str_replace('/', '-', $request->date)));
        $holiday = Holidays::where('id', $id)->firstOrFail();
        $holiday->update([
            'holiday_name' => $request->holiday_name,
            'date' => $data,
            'type' => $request->type,
            'status' => $request->status
        ]);
        return redirect()->route('holiday')->with('success','Holiday Updated Successfully.');
    }

}
