<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Manager;
use App\Models\project_manager;
use App\Models\Skills;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Technology;

class AttendanceController extends Controller
{
    /*project curd opration*/
    public function project_add(){
        $Projects = Project::latest()->paginate(20);
        return view('project.project',compact('Projects'))->with('i', (request()->input('page', 1) - 1) * 20);
    }

    public function project_save(Request $request)
    {
        $request->validate([
            'project_name' => 'required',
            'vendor_name' =>  'required',
            'project_startdate' =>  'required',
            'is_billable' =>  'required',
        ]);
        Project::create($request->all());
        return redirect()->route('project')->with('success','Project Created Successfully.');
    }

    public function project_edit($id)
    {
        $project = Project::where('id', $id)->firstOrFail();

        return view('project.projectedit', compact('project'));
    }

    public function project_update(Request $request, $id, $status)
    {
        $project = Project::where('id', $id)->firstOrFail();
        $project->update(['status' => $status]);
  
        return redirect()->route('project')->with('success','Project Updated Successfully');
    }
    public function project_delete($del_id){
    $project=Project::find($del_id);
    $project->delete($project->id);
    return redirect()->route('project')->with('success','Project Deleted Successfully'); 
    }

    public function project_filter(Request $request)
    {
        $projectData = DB::select("select * from projects where project_name like '%{$request->search}%' OR vendor_name like '%{$request->search}%'");
        return json_encode($projectData);
    }

    /* close project curd opration*/

    /*skill curd opration*/
    public function skill_add(){
        $skills = Skills::latest()->paginate(5);
        return view('skill.skill',compact('skills'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function skill_save(Request $request)
    {
        $request->validate([
            'skill_name' => 'required',
        ]);
        Skills::create($request->all());
        return redirect()->route('skill')->with('success','Skill Created Successfully.');
    }
    

    public function skill_edit($id)
    {
        $skill = Skills::where('id', $id)->firstOrFail();

        return view('skill.skilledit', compact('skill'));
    }

    public function skill_update(Request $request, $id)
    {
        $request->validate([
            'skill_name' => 'required',
        ]);
        $skill = Skills::where('id', $id)->firstOrFail();
       $skill->update($request->all());
  
        return redirect()->route('skill')->with('success','Skill Updated Successfully');
    }
    public function skill_delete($del_id){
    $skill=Skills::find($del_id);
    $skill->delete($skill->id);
    return redirect()->route('skill')->with('success','Skill Deleted Successfully'); 
    }

    /* close skill curd opration*/

    /* Open manager curd opration*/

    public function manager_add(){
        $skills = Skills::all();
        $managers = Manager::latest()->paginate(5);
        return view('manager.manager',[
            'skills' => $skills
        ],compact('managers'))->with('i', (request()->input('page', 1) - 1) * 5);
    }
    public function manager_save(Request $request)
    {
        $request->validate([
            'manager_name' => 'required',
            'skill_type'  => 'required',
            'status' => 'required',
        ]);
        $skill_type=implode(",",$request->skill_type);
        Manager::create([
           'manager_name'=>$request->manager_name,
           'skill_type'=>$skill_type,
           'status'=>$request->status
       ]);
       return redirect()->route('manager')->with('success','Manager Created Successfully.');
    }

    public function manager_edit($id)
    {
        $skills = Skills::all();
        $manager = Manager::where('id', $id)->firstOrFail();

        return view('manager.manageredit', [
            'skills' => $skills
        ],compact('manager'));
    }

    public function manager_update(Request $request, $id)
    {
        $request->validate([
            'manager_name' => 'required',
            'skill_type'  => 'required',
            'status' => 'required',
        ]);
        $skill_type=implode(",",$request->skill_type);
        $manager = Manager::where('id', $id)->firstOrFail();
           $manager->update([
               'manager_name'=>$request->manager_name,
               'skill_type'=>$skill_type,
               'status'=>$request->status
           ]);
  
        return redirect()->route('manager')->with('success','Manager Updated Successfully');
    }
    public function manager_delete($del_id){
    $manager=Manager::find($del_id);
    $manager->delete($manager->id);
    return redirect()->route('manager')->with('success','Manager Deleted Successfully'); 
    }
    /*close manager curd opration*/

    public function project_manager_add()
    {
        $project = Project::where('status','=','1')->get();
        $allTechnology = Technology::where('status','=','1')->get();
        $users = DB::select("SELECT users.id, users.name FROM users
            LEFT JOIN emp_registrations ON users.id=emp_registrations.user_id
            WHERE emp_registrations.status='1'");
        $managers = DB::table('users')
            ->leftjoin('emp_registrations','emp_registrations.user_id','=','users.id')
            ->where('users.role', 4)
            ->where('emp_registrations.status','1')
            ->orwhere('users.role', 6)
            ->orwhere('users.role', 5)
            ->paginate(150,array('users.id','users.name'));
        $projectmanagers = project_manager::latest()->paginate(5);
        $managersarray = array();
        foreach($managers as $managersData){
            $managersarray[$managersData->id]=$managersData->name;
        }

        if(Auth::user()->role == 4){
            $project_dev_list = DB::select("select emp_technology.tech_name, projects.project_name, users.name, project_managers.manager_id, project_managers.id, project_managers.status from project_managers
                left join projects on project_managers.project_id=projects.id
                left join users on project_managers.developer_id=users.id
                left join emp_technology on project_managers.technology_id=emp_technology.id
                where project_managers.manager_id=".Auth::user()->id);
        }else{
            $project_dev_list = DB::select("select emp_technology.tech_name, projects.project_name, users.name, project_managers.manager_id, project_managers.id, project_managers.status from project_managers
                left join projects on project_managers.project_id=projects.id
                left join users on project_managers.developer_id=users.id
                left join emp_technology on project_managers.technology_id=emp_technology.id
                ");
        }
        return view('projectmanagers.projectmanager',[
            'project' => $project,
            'allTechnology' => $allTechnology,
            'users' => $users,
            'managers' => $managers,
            'managerdatas' => $managersarray,
            'proj_dev_list'=>$project_dev_list
        ],compact('projectmanagers'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function assignproject_filter(Request $request)
    {
        $project = Project::where('status','=','1')->get();
        $allTechnology = Technology::where('status','=','1')->get();
        $users = DB::select("SELECT users.id, users.name FROM users
            LEFT JOIN emp_registrations ON users.id=emp_registrations.user_id
            WHERE emp_registrations.status='1'");
        $managers = DB::table('users')
            ->leftjoin('emp_registrations','emp_registrations.user_id','=','users.id')
            ->where('users.role', 4)
            ->where('emp_registrations.status','1')
            ->orwhere('users.role', 6)
            ->paginate(150,array('users.id','users.name'));
        $projectmanagers = project_manager::latest()->paginate(5);
        $managersarray = array();
        foreach($managers as $managersData){
            $managersarray[$managersData->id]=$managersData->name;
        }

        if(Auth::user()->role == 4){
            $project_dev_list = DB::select("select emp_technology.tech_name, projects.project_name, users.name, project_managers.manager_id, project_managers.id, project_managers.status from project_managers
                left join projects on project_managers.project_id=projects.id
                left join users on project_managers.developer_id=users.id
                left join emp_technology on project_managers.technology_id=emp_technology.id
                where project_managers.manager_id=".Auth::user()->id." and projects.project_name like '%{$request->search}%' OR emp_technology.tech_name like '%{$request->search}%' OR users.name like '%{$request->search}%'");
        }else{
            $project_dev_list = DB::select("select emp_technology.tech_name, projects.project_name, users.name, project_managers.manager_id, project_managers.id, project_managers.status from project_managers
                left join projects on project_managers.project_id=projects.id
                left join users on project_managers.developer_id=users.id
                left join emp_technology on project_managers.technology_id=emp_technology.id
                where projects.project_name like '%{$request->search}%' OR emp_technology.tech_name like '%{$request->search}%' OR users.name like '%{$request->search}%'");
        }
        
        return view('projectmanagers.projectmanager',[
            'project' => $project,
            'allTechnology' => $allTechnology,
            'users' => $users,
            'managers' => $managers,
            'managerdatas' => $managersarray,
            'proj_dev_list'=>$project_dev_list
        ],compact('projectmanagers'))->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function manager_details()
    {
        $managers = DB::table('users')
            ->leftjoin('emp_registrations','emp_registrations.user_id','=','users.id')
            ->where('users.role', 4)
            ->where('emp_registrations.status','1')
            ->orwhere('users.role', 6)
            ->paginate(150,array('users.id','users.name'));
        $projectlistdata = [];

        return view('manager.managerdetails',[
            'managers' => $managers,
            'projectlistdata' => $projectlistdata
        ]);
    }

    public function managerdetails_filter(Request $request)
    {
        $request->validate([
            'manager_id' => 'required'
        ]);

        $managers = DB::table('users')
            ->leftjoin('emp_registrations','emp_registrations.user_id','=','users.id')
            ->where('users.role', 4)
            ->where('emp_registrations.status','1')
            ->orwhere('users.role', 6)
            ->paginate(150,array('users.id','users.name'));

        $managersarray = array();
        foreach($managers as $managersData){
            $managersarray[$managersData->id]=$managersData->name;
        }

        $projectlistdata = DB::select("select projects.project_name, users.name, project_managers.manager_id, project_managers.id, project_managers.status from project_managers
            left join projects on project_managers.project_id=projects.id
            left join users on project_managers.developer_id=users.id
            where project_managers.status='1' and (project_managers.manager_id = $request->manager_id OR project_managers.developer_id = $request->manager_id)");

        return view('manager.managerdetails',[
            'managers' => $managers,
            'managerdatas' => $managersarray,
            'projectlistdata'=>$projectlistdata
        ]);
    }

    public function project_manager_save(Request $request)
    {
        $request->validate([
            'project_id' => 'required',
            'manager_id'  => 'required',
            'developer_id'  => 'required',
            'technology_id'  => 'required',
            'status'  => 'required',
        ]);
        project_manager::create($request->all());
        return redirect()->route('projectmanager')->with('success','Project Assign Successfully.');
    }

    public function projectmanager_update(Request $request, $id, $status)
    {
        $project_manager = project_manager::where('id', $id)->firstOrFail();
        $project_manager->update(['status' => $status]);
  
        return redirect()->route('projectmanager')->with('success','Project Assign Updated Successfully');
    }

    public function projectmanager_delete($del_id){
        $project_manager=project_manager::find($del_id);
        $project_manager->delete($project_manager->id);
        
        return redirect()->route('projectmanager')->with('success','Project Assign Deleted Successfully'); 
    }
}
