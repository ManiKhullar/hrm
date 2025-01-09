<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeamLead;
use App\Models\TimeSheetComments;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Helper\SendMail;
use DateTime;

class TeamLeadController extends Controller
{
    public function teamlead_add()
    {
        $user_id = Auth::user()->id;
        
        if((Auth::user()->role) == 4)
        {
            $userData =  DB::select("select DISTINCT users.id, users.name from users
                left join project_managers on users.id=project_managers.developer_id
                where project_managers.status='1' and project_managers.manager_id=$user_id");

                $teamlead =  DB::select("select DISTINCT users.id, users.name,team_lead.status from users
                left join team_lead on users.id=team_lead.teamlead_id
                where team_lead.status='1' and team_lead.manager_id=$user_id");   
                $teamLead = array();
                foreach($teamlead as $tl){
                    $teamLead[] = $tl->id;
                }
                if(!empty($teamLead)){
                    $tl = implode(',',$teamLead) ;
                    $teamMember =  DB::select("select DISTINCT users.id, users.name,team_lead.teamlead_id from users
                    left join team_lead on users.id=team_lead.user_id
                    where team_lead.status='1' and team_lead.teamlead_id in ($tl)"); 
                 } else{
                    $teamMember = [];
                 }

            return view('team_lead.teamlead_add',[
                        'userData' => $userData,
                        'team_lead' => $teamlead,
                        'teamMember' => $teamMember
                ])->with('i', (request()->input('page', 1) - 1) * 5);
        }
        if((Auth::user()->role) == 5 || (Auth::user()->role) == 6)
        {
            $userData =  DB::select("select users.id, users.name from users
            left join emp_registrations on users.id=emp_registrations.user_id
            where emp_registrations.status='1'");

            $teamlead =  DB::select("select DISTINCT users.id, users.name from users
                left join team_lead on users.id=team_lead.teamlead_id
                where team_lead.status='1'");    
            

            return view('team_lead.teamlead_add',[
                'userData' => $userData,
                'team_lead' => $teamlead
        ])->with('i', (request()->input('page', 1) - 1) * 5);
        }
    }

    public function add_teamlead(Request $request)
    { 
        $manager_id = Auth::user()->id;
        $teamLead = $request->emp_id;
        $member_id = $request->user_id;

        foreach($member_id as $member){
            if(!empty($member)){
                    TeamLead::create([
                        'manager_id' => $manager_id,
                        'user_id' => $member,
                        'teamlead_id' => $teamLead,
                        'status' =>  '1',
                        'updated_at'=>now(),
                        'created_at'=>now()
                    ]);
                }
         }
         $update = DB::table('users')
                ->where('id', $teamLead)
                ->update(array(
                    'role' => 7
                ));
        return redirect()->route('team_lead')->with('success','Team Lead Added Successfully.');
    }

    public function delete_team_lead(Request $request)
    {
        $claimImg = TeamLead::where('teamlead_id',$request->id)->delete();
       // $claimImg->delete();
       $update = DB::table('users')
       ->where('id', $request->id)
       ->update(array(
           'role' => 2
       ));
        return redirect()->route('team_lead')->with('success','Team Lead Deleted Successfully.');
    }
}
