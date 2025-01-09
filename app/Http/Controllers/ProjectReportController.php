<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\User;
use App\Models\Skills;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Technology;

class ProjectReportController extends Controller
{
    /*project curd opration*/
    public function projectreport(Request $request){
        
        $Join =" left join project_managers as project_managers on project_managers.project_id = projects.id ";
        $query = "where projects.id > 0 ";
        if(isset($request->status))
            $query .= " and projects.status = '".$request->status."'";
        if(isset($request->is_billable))
           $query .= " and projects.is_billable = '".$request->is_billable."'";
        if(isset($request->skill))
        $query .= " and project_managers.technology_id = ".$request->skill;
        if(isset($request->vendor))
        $query .= " and projects.vendor_name = '".$request->vendor."'";
        if(isset($request->user_id)){
        $query .= " and ( project_managers.manager_id =".$request->user_id. " or  project_managers.developer_id=" .$request->user_id.")";
        
        }
        if(isset($request->skill) || isset($request->user_id))
        $sql = "select projects.*,project_managers.technology_id as skill,project_managers.status as st,project_managers.manager_id as mid,project_managers.developer_id as devid from projects as projects $Join ".$query." group by projects.id order by projects.project_name ASC";
        else
        $sql = "select projects.* from projects as projects ".$query." order by projects.project_name ASC";
        $projectList = DB::select($sql);
        $Projects = Project::all();
        $Users = User::all();
        $Skills = Technology::where('status','=','1')->get();
        $Vendors = DB::select("select vendor_name from projects group by vendor_name");
        return view('reports.report',['req'=>$request,'projectList'=>$projectList,'vendors'=>$Vendors,'projects'=>$Projects,'skills'=>$Skills,'users'=>$Users]);
    }

    public function empListReport(Request $request){
        $cond = "";
        if(isset($request->skill) && $request->skill >0){
            $cond .= " and pm.technology_id = '".$request->skill."'";
        }
        $sql = "select u.employee_code,u.name,u.email,em.tech_name from users as u 
                left join project_managers as pm on u.id=pm.developer_id left join emp_technology 
                as em on em.id = pm.technology_id left join emp_registrations as er on  er.user_id=u.id where u.role not in (4,5,6) and er.status = '1' and pm.status = '1'".$cond." and pm.technology_id >0  
                group by u.employee_code order by pm.id DESC";
        $empList = DB::select($sql);
        $sql = "select u.employee_code,u.name,u.email,em.tech_name from users as u 
                left join project_managers as pm on u.id=pm.manager_id left join emp_technology 
                as em on em.id = pm.technology_id left join emp_registrations as er on  er.user_id=u.id where er.status = '1' and pm.status = '1'".$cond." and pm.technology_id >0  
                group by u.employee_code order by pm.id DESC";
        $managerList = DB::select($sql);
        $Skills = Technology::where('status','=','1')->get();
        return view('reports.empreport',['req'=>$request,'managerList'=>$managerList,'empList'=>$empList,'skills'=>$Skills]);       
    }

    public function banchEmpList(Request $request){

        //$sql="select u.id from projects where status='1' and is_billable='1'";
       /* $sql = "select u.id,u.employee_code,u.name,u.email from users as u 
                left join project_managers as pm on u.id=pm.developer_id left join projects as p on p.id=pm.project_id
                where p.status='1' and p.is_billable='1' and pm.status='1'"; */
                $sql = "select u.id from users as u 
                left join project_managers as pm on u.id=pm.developer_id left join projects as p on p.id=pm.project_id
                where p.status='1' and p.is_billable='1' and pm.status='1'";
        $billableProjectList = DB::select($sql);
        $BillableArray = [];
        foreach($billableProjectList as $rec){
            $BillableArray[] = $rec->id;
        }
//print_r($BillableArray); exit;
        $sql="select u.employee_code, u.id,u.is_paid,u.name,u.email,pm.technology_id from users as u 
        left join emp_registrations as er on u.id=er.user_id
                left join project_managers as pm on u.id=pm.developer_id left join projects as p on p.id=pm.project_id
                where p.status='1' and p.is_billable='0' and u.is_paid=1 and pm.status='1' and er.status='1' and u.id not in (".implode(',',$BillableArray).") group by u.employee_code order by pm.technology_id ASC";
        $nonBillableProjectList = DB::select($sql);
        $Skills = Technology::where('status','=','1')->get();
        $sql="select u.employee_code, u.id,u.is_paid,u.name,u.email,pm.technology_id from users as u 
        left join emp_registrations as er on u.id=er.user_id
                left join project_managers as pm on u.id=pm.developer_id left join projects as p on p.id=pm.project_id
                where p.status='1' and p.is_billable='0' and u.is_paid=0 and pm.status='1' and er.status='1' and u.id not in (".implode(',',$BillableArray).") group by u.employee_code order by pm.technology_id ASC";
        $nonPaidempList = DB::select($sql);
        return view('reports.bancheport',['req'=>$request,'emplist'=>$nonPaidempList,'paidempList'=>$nonBillableProjectList,'skills'=>$Skills]);
    }


    
    
    
    
   

    
    
    
}
