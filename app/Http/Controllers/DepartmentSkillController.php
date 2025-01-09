<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DepartmentSkill;
use Illuminate\Support\Facades\DB;

class DepartmentSkillController extends Controller
{

    public function depskill_add(){
        $depskills = DB::table('skill_department')->paginate(15);
        return view('skilldepartment.depskill',['depskills'=> $depskills])->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function depskill_save(Request $request)
    {
        $request->validate([
            'skill_name' => 'required'
        ]);
        $skillName = DB::select("select * from skill_department where skill_name='".$request->skill_name."'");

        if(empty($skillName)){
            DepartmentSkill::create([
                'skill_name' => $request->skill_name,
                // 'status' =>  $request->status,
            ]);
            return redirect()->route('depskill')->with('success','Department Skill Added Successfully.');
        } else{
            return redirect()->route('depskill')->with('success','Dublicate Entry.');
        }
    }

    public function depskill_edit($id)
    {
        $depskill = DepartmentSkill::where('id', $id)->firstOrFail();
        return view('skilldepartment.depskilledit', compact('depskill'));
    }

    public function depskill_update(Request $request, $id)
    {
        $role = DepartmentSkill::where('id', $id)->firstOrFail();
        $role->update([
            'status' =>  $request->status
        ]);
  
        return redirect()->route('depskill')->with('success','Department Skill Updated Successfully');
    }

    public function depskill_delete($del_id)
    {
        $role=DepartmentSkill::find($del_id);
        $role->delete($role->id);
        return redirect()->route('depskill')->with('success','Department Skill Deleted Successfully'); 
    }

}
