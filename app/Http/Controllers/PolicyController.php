<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmpPolicy;
use App\Models\EmpRegistrations;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Helper\SendMail;

class PolicyController extends Controller
{
    public function policy_add(){
        $policyData = EmpPolicy::all();
        return view('policy.policy',['policyData'=> $policyData])->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function policy_save(Request $request)
    {
        $path = public_path('policy/images/');
            !is_dir($path) &&
            mkdir($path, 0777, true);
        if($request->id == NULL)
        {
            $request->validate([
                'hr_policy_leave_mang' => 'required|mimes:pdf|max:10240',
                'hr_process_onboarding' => 'required|mimes:pdf|max:10240',
                'hr_process_offboarding' => 'required|mimes:pdf|max:10240'
            ]);
    
            $data = $request->file('hr_policy_leave_mang');
            $file_name = rand(10,999).$data->getClientOriginalName();
            $data->move($path, $file_name);

            $data_onboarding = $request->file('hr_process_onboarding');
            $filename = rand(10,999).$data_onboarding->getClientOriginalName();
            $data_onboarding->move($path, $filename);

            $data_offboarding = $request->file('hr_process_offboarding');
            $name = rand(10,999).$data_offboarding->getClientOriginalName();
            $data_offboarding->move($path, $name);
           
            EmpPolicy::create([
                'hr_policy_leave_mang' => $file_name,
                'hr_process_onboarding' => $filename,
                'hr_process_offboarding' => $name
            ]);
            return redirect()->route('addpolicy')->with('success','Policy Added Successfully.');
        } else{
            $allowed = array('pdf');
            $filename = rand(10,999).$_FILES['hr_policy_leave_mang']['name'];
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (in_array($ext, $allowed)) {
                $data = $request->file('hr_policy_leave_mang');
                $data->move($path, $filename);

                $policy = EmpPolicy::where('id', $request->id)->firstOrFail();
                unlink($path.$policy->hr_policy_leave_mang);
                $policy->update([
                    'hr_policy_leave_mang' => $filename
                ]);
            }
            $file_name = rand(10,999).$_FILES['hr_process_onboarding']['name'];
            $exten = pathinfo($file_name, PATHINFO_EXTENSION);
            if (in_array($exten, $allowed)) {
                $data = $request->file('hr_process_onboarding');
                $data->move($path, $file_name);

                $policy = EmpPolicy::where('id', $request->id)->firstOrFail();
                unlink($path.$policy->hr_process_onboarding);
                $policy->update([
                    'hr_process_onboarding' => $file_name
                ]);
            }
            $name = rand(10,999).$_FILES['hr_process_offboarding']['name'];
            $extension = pathinfo($name, PATHINFO_EXTENSION);
            if (in_array($extension, $allowed)) {
                $data = $request->file('hr_process_offboarding');
                $data->move($path, $name);

                $policy = EmpPolicy::where('id', $request->id)->firstOrFail();
                unlink($path.$policy->hr_process_offboarding);
                $policy->update([
                    'hr_process_offboarding' => $name
                ]);
            }
            return redirect()->route('addpolicy')->with('success','Policy Updated Successfully.');
        }
         
    }

    public function policy_edit(){
        $policyData = EmpPolicy::all();
        return view('policy.policyedit',['policyData'=> $policyData])->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function policy_update(Request $request)
    {
        $request->validate([
            'status' => 'required'
        ]);

        $userId = Auth::user()->id;
        
        $emailTemplate = DB::select("SELECT * FROM email_templates WHERE type = 'agree_policy' AND status = '1'");
        $userData = DB::select("SELECT name, email FROM users WHERE id = $userId");

        $html = str_replace("{{##USERNAME$}}",$userData[0]->name,$emailTemplate[0]->content);
        $from = 'noreply@mybluethink.in';
        $to = $userData[0]->email;
        $cc = 'hr@bluethink.in';

        $empReg = EmpRegistrations::where('user_id', $userId)->firstOrFail();
        $empReg->update([
            'accept_policy' =>  'Accepted'
        ]);
        SendMail::sendMail($html, $emailTemplate[0]->subject, $to, $from, $cc);
        return redirect()->route('policyedit')->with('success','You Have Read Terms & Condition Successfully');
    }
}
