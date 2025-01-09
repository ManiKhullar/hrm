<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmpSkill;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EmpSkillController extends Controller
{

    public function empskill_add(){
        $user_id = Auth::user()->id;
        $skillNames = DB::select("select * from skill_department where status = '1'");
        $empskills = DB::table('emp_skill')
            ->leftjoin("skill_department", "emp_skill.skill_id", '=', "skill_department.id")
            ->leftjoin("users", "emp_skill.user_id", '=', "users.id")
            ->where('emp_skill.user_id', $user_id)
            ->paginate(15,array('emp_skill.id','skill_department.skill_name','users.name','emp_skill.skill_level','emp_skill.experience','emp_skill.status'));

        return view('empskill.empskill',[
            'empskills'=> $empskills,
            'skillNames'=> $skillNames
            ])->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function empskill_save(Request $request)
    {
        $user_id = Auth::user()->id;
        $request->validate([
            'skill_id' => 'required',
            'skill_level' => 'required',
            'experience' => 'required'
        ]);

        $empSkill = DB::select("select * from emp_skill WHERE skill_id='".$request->skill_id."' AND user_id='".$user_id."'");

        if(empty($empSkill)){
            EmpSkill::create([
                'skill_id' => $request->skill_id,
                'user_id' =>  $user_id,
                'skill_level' => $request->skill_level,
                'experience' => $request->experience,
            ]);
            return redirect()->route('empskill')->with('success','Employee Skill Added Successfully.');
        } else{
            return redirect()->route('empskill')->with('success','Dublicate Entry.');
        }
    }

    public function empskill_edit($id)
    {
        $skillNames = DB::select("select * from skill_department where status = '1'");
        $empskills = EmpSkill::where('id', $id)->firstOrFail();
        return view('empskill.empskilledit', compact('skillNames','empskills'));
    }

    public function empskill_update(Request $request, $id)
    {
        $request->validate([
            'skill_level' => 'required',
            'experience' => 'required',
            'status' => 'required'
        ]);
        $role = EmpSkill::where('id', $id)->firstOrFail();
        $role->update([
            'skill_level' => $request->skill_level,
            'experience' => $request->experience,
            'status' =>  $request->status
        ]);
  
        return redirect()->route('empskill')->with('success','Employee Skill Updated Successfully');
    }

    public function empskill_delete($del_id)
    {
        $empskill=EmpSkill::find($del_id);
        $empskill->delete($empskill->id);
        return redirect()->route('empskill')->with('success','Employee Skill Deleted Successfully'); 
    }

}
