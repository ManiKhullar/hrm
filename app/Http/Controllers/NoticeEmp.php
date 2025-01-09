<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmpNotice;
use Illuminate\Support\Facades\DB;

class NoticeEmp extends Controller
{
    public function notice_add(){
        $emp_notice = DB::select("SELECT * FROM emp_notice");
        return view('notice.notice',['emp_notice'=> $emp_notice])->with('i', (request()->input('page', 1) - 1) * 5);
    }
    
    public function notice_edit($id)
    {
        $notice = EmpNotice::where('id', $id)->firstOrFail();

        return view('notice.noticeedit', compact('notice'));
    } 

    public function notice_update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required',
            'status' => 'required',
        ]);
        $notice = EmpNotice::where('id', $id)->firstOrFail();
        $notice->update([
            'color' => $request->color,
            'content' =>  $request->content,
            'status' =>  $request->status,
        ]);
        return redirect()->route('notice')->with('success','Employee Notice Updated Successfully.');
    }
}
