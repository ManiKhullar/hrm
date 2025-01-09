<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Claim;
use App\Models\ClaimImages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ClaimController extends Controller
{
    public function claim_add(){
        $user_id = Auth::user()->id;
        $claims = DB::table('claim')
            ->leftJoin('users','users.id','=','claim.approval_by')
            ->where('claim.user_id', $user_id)
            ->paginate(10,array('users.name','claim.id','claim.category','claim.start_date','claim.end_date','claim.amount','claim.status','claim.description','claim.manager_comment'));
        return view('claim.claim',['claims'=> $claims])->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function claim_save(Request $request)
    {
        $user_id = Auth::user()->id;
        $request->validate([
            'category' => 'required',
            'from' => 'required',
            'to' => 'required',
            'amount' => 'required',
            'file_upload' => 'required|max:2048'
        ]);

        $query = "user_id=".$user_id." AND category='".$request->category."' AND start_date='".date('Y-m-d',strtotime($request->from)).
        "' AND end_date='".date('Y-m-d',strtotime($request->to))."'";

        if($request->mobile){
            $query .= " AND mobile='".$request->mobile."'";
        }
        $claimData = DB::select("SELECT * FROM claim WHERE ". $query ." AND (status='Pending' OR status='Approve')");

        if(empty($claimData)){
            $path = public_path('claims/images/');
            !is_dir($path) &&
                mkdir($path, 0777, true);

            $claim_id = Claim::create([
                'user_id' => $request->user()->id,
                'category' => $request->category,
                'mobile' => $request->mobile,
                'start_date' => date('Y-m-d',strtotime($request->from)),
                'end_date' => date('Y-m-d',strtotime($request->to)),
                'amount' => $request->amount,
                'description' => $request->description,
            ])->id;
            foreach($request->file('file_upload') as $data){
                $file_name = rand(10,99).strtotime(date("Y-m-d h:i:s")).'.'.$data->extension();
                $data->move($path, $file_name);
                ClaimImages::create([
                    'claim_id' => $claim_id,
                    'file_upload' => $file_name
                ]);
            }
            return redirect()->route('claim')->with('success','Claim Added Successfully.');
        } else{
            return redirect()->route('claim')->with('error','Dublicate Entry.');
        }  
    }

    public function claim_edit($id)
    {
        $claim = Claim::where('id', $id)->firstOrFail();
        $claimImg = ClaimImages::where('claim_id',$id)->paginate(15);
        return view('claim.claimedit', compact('claim','claimImg'));
    }

    public function claim_update(Request $request, $id)
    {   
        $request->validate([
            'category' => 'required',
            'from' => 'required',
            'to' => 'required',
            'amount' => 'required',
            'file_upload' => 'required'
        ]);     
        $path = public_path('claims/images/');
            !is_dir($path) &&
             mkdir($path, 0777, true);

        foreach($request->file('file_upload') as $data){
            $file_name = rand(10,99).strtotime(date("Y-m-d h:i:s")).'.'.$data->extension();
            $data->move($path, $file_name);
            ClaimImages::create([
                'claim_id' => $id,
                'file_upload' => $file_name
            ]);
        }
        $claim = Claim::where('id', $id)->firstOrFail();
        $claim->update([
            'start_date' => date('Y-m-d',strtotime($request->from)),
            'end_date' => date('Y-m-d',strtotime($request->to)),
            'amount' => $request->amount,
            'description' =>  $request->description,
        ]);
        return redirect()->route('claim')->with('success','Claim Updated Successfully');
    }

    public function delete_img(Request $request)
    {
        $claimImg = ClaimImages::where('id',$request->id)->firstOrFail();
        $claimImg->delete();
        $path = public_path('claims/images/'.$claimImg->file_upload);
        unlink($path);
        $allImg = DB::select("select * from claim_images where claim_id = ".$claimImg->claim_id);
        $data=[];
        foreach ($allImg as $value) {
            $data[] = array(
                'file_upload' => $value->file_upload,
                'id' => $value->id,
                'path' => url('claims/images/'.$value->file_upload)
            );
        }
        return json_encode($data);
    }

    public function claim_filter(Request $request)
    {
        $current_user = Auth::user()->id;
        if((Auth::user()->role) == 4)
        {
            $claimList = DB::table('claim')
                ->leftJoin('claim_images','claim_images.claim_id','=','claim.id')
                ->leftJoin('users','users.id','=','claim.user_id')
                ->leftJoin('project_managers','project_managers.developer_id','=','claim.user_id')
                ->where([
                    ['project_managers.manager_id',$current_user],
                    ['users.role','2']
                ])
                ->orWhere('users.name','LIKE',"%{$request->search}%")
                ->orWhere('claim.status','LIKE',"%{$request->search}%")
                ->orWhere('claim.amount','LIKE',"%{$request->search}%")
                ->orWhere('claim.start_date','LIKE',"%{$request->search}%")
                ->orWhere('claim.end_date','LIKE',"%{$request->search}%")
                ->orderBy('claim.start_date','DESC')
                ->paginate(10,array('users.name','claim.id','claim.category','claim.start_date','claim.end_date','claim.amount','claim.status','claim.description'));
            
            if(!empty($request->search)){
                $claimList->appends(['search'=>$request->search]);
            }
            return view('claim.approveclaimlist',['claimList'=> $claimList])->with('i', (request()->input('page', 1) - 1) * 10);
        }
        if((Auth::user()->role) == 3 || (Auth::user()->role) == 5 || (Auth::user()->role) == 6)
        {
            $claimList = DB::table('claim')
                ->leftJoin('users','users.id','=','claim.user_id')
                ->where('users.name','LIKE',"%{$request->search}%")
                ->orWhere('claim.status','LIKE',"%{$request->search}%")
                ->orWhere('claim.amount','LIKE',"%{$request->search}%")
                ->orWhere('claim.start_date','LIKE',"%{$request->search}%")
                ->orWhere('claim.end_date','LIKE',"%{$request->search}%")
                ->orderBy('claim.start_date','DESC')
                ->paginate(10,array('users.name','claim.id','claim.category','claim.start_date','claim.end_date','claim.amount','claim.status','claim.description'));
            
            if(!empty($request->search)){
                $claimList->appends(['search'=>$request->search]);
            }
            return view('claim.approveclaimlist',['claimList'=> $claimList])->with('i', (request()->input('page', 1) - 1) * 10);
        }
    }

    public function approve_claim()
    {
        $current_user = Auth::user()->id;
        if((Auth::user()->role) == 4)
        {
            $claimList = DB::table('claim')
                ->leftJoin('claim_images','claim_images.claim_id','=','claim.id')
                ->leftJoin('users','users.id','=','claim.user_id')
                ->leftJoin('emp_registrations','emp_registrations.user_id','=','claim.user_id')
                ->leftJoin('project_managers','project_managers.developer_id','=','claim.user_id')
                ->where([
                    ['project_managers.manager_id',$current_user],
                    ['users.role','2'],
                    ['emp_registrations.status','1']
                ])
                ->groupBy('claim.id')
                ->paginate(10,array('users.name','claim.id','claim.category','claim.start_date','claim.end_date','claim.amount','claim.status','claim.description'));
            
            return view('claim.approveclaimlist',['claimList'=> $claimList])->with('i', (request()->input('page', 1) - 1) * 10);
        }
        if((Auth::user()->role) == 3 || (Auth::user()->role) == 5 || (Auth::user()->role) == 6)
        {
            $claimList = DB::table('claim')
                ->leftJoin('users','users.id','=','claim.user_id')
                ->leftJoin('emp_registrations','emp_registrations.user_id','=','claim.user_id')
                ->where('emp_registrations.status','1')
                ->orderBy('claim.start_date','DESC')
                ->paginate(10,array('users.name','claim.id','claim.category','claim.start_date','claim.end_date','claim.amount','claim.status','claim.description'));
            
            return view('claim.approveclaimlist',['claimList'=> $claimList])->with('i', (request()->input('page', 1) - 1) * 10);
        }
    }

    public function approveclaim_edit($id)
    {
        $claim = Claim::where('id', $id)->firstOrFail();
        $claimImg = ClaimImages::where('claim_id',$id)->paginate(15);
        if((Auth::user()->role) == 3 || (Auth::user()->role) == 4 || (Auth::user()->role) == 5 || (Auth::user()->role) == 6)
        {
            return view('claim.approveclaimedit', compact('claim','claimImg'));
        }else{
            return view('claim.claimedit', compact('claim','claimImg'));
        }     
    }

    public function approveclaim_update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
            'manager_comment' => 'required'
        ]);
        if((Auth::user()->role) == 3 || (Auth::user()->role) == 4 || (Auth::user()->role) == 5 || (Auth::user()->role) == 6)
        {
            $claim = Claim::where('id', $id)->firstOrFail();
            $claim->update([
                'status' => $request->status,
                'approval_by' => $request->user()->id,
                'manager_comment' =>  $request->manager_comment,
            ]);
            return redirect()->route('approveclaim')->with('success','Claim Update Successfully');
        }else{
            return redirect()->route('approveclaim')->with('success','Unauthorized Access');
        }
    }
}
