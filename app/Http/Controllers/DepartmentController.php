<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    /*department curd opration*/

    public function department_add(){
        $departments = DB::table('department')->paginate(15);
        return view('department.department',['departments'=> $departments])->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function department_save(Request $request)
    {
        $request->validate([
            'department_name' => 'required',
            'status' =>  'required',
        ]);
        Department::create([
            'department_name' => $request->department_name,
            'status' =>  $request->status,
        ]);
        return redirect()->route('department')->with('success','Department Created Successfully.');
    }

    public function department_update(Request $request, $id, $status)
    {
        $department = Department::where('id', $id)->firstOrFail();
        $department->update([
            'status' =>  $status,
        ]);
  
        return redirect()->route('department')->with('success','Department Updated Successfully');
    }
}
