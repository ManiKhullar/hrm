<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use App\Models\EmpSalary;
use DateInterval;
use App\Models\User;
use App\Models\EmpRegistrations;
use App\Models\EmpAddress;
use App\Models\EmpPerAddress;
use App\Models\EmpFamilyDetail;
use App\Models\EmpCommunication;
use App\Models\EmpPrevEmployment;
use App\Models\EmpAccountDetails;
use App\Models\EmpAccount;
use App\Models\Skills;
use App\Models\EmailTemplates;
use App\Http\Helper\SendMail;
use DateTime;
use DatePeriod;

class EmployeeImportController extends Controller
{
    public function empimport(){
        return view('empimport.index');
    }

    public function empimport_data(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);
        $path = public_path('empImport/');
        !is_dir($path) &&
            mkdir($path, 0777, true);

        $data = $request->file('file');
        $data->move($path, $data->getClientOriginalName());
        $fileName = $data->getClientOriginalName();
       $filePath = $path.$fileName;
       
       $file = fopen($filePath , 'r');
        if (!$file) {
            fclose($file);
            throw new \Exception("Could not open file: {$filePath}");
        }

        $columnHeaders = [];
        $importData = [];
        $counter = 0;
        $mailflag = false;
        while ($data = fgetcsv($file, 100000, ",")) {
            if ($counter == 0) {
                $columnHeaders = $data;
                $counter++;
                continue;
            }
            $mailflag = false;
            $dataValue = array_combine($columnHeaders, $data);
            $importData[] = $dataValue;

            if(trim($importData[$counter-1]['department']) === ''){
                return redirect()->route('empimport')->with('error','Please fill department column data.');
            }else{
                $depId = DB::select("SELECT id FROM department WHERE department_name = '".$importData[$counter-1]['department']."'");
                if($depId[0]->id)
                { 
                    $userId = DB::select("SELECT * FROM users WHERE employee_code = '".$importData[$counter-1]['employee_id']."'");
                    if(isset($userId[0]->id) && ($userId[0]->id)!=''){
                        $users = DB::table('users')
                            ->where('id', $userId[0]->id)
                            ->update(array(
                                'name' => $importData[$counter-1]['employee_name'],
                                'employee_code' => $importData[$counter-1]['employee_id'],
                                'role' => $depId[0]->id,
                                'email' => $importData[$counter-1]['emp_official_email']
                            ));
                    $id = $userId[0]->id;
                    $mailflag = false;
                    }else{
                        $emailTemplate = DB::select("SELECT * FROM email_templates WHERE type = 'import_users' AND status = '1'");
                        $to = $importData[$counter-1]['emp_official_email'];
                        $from = 'noreply@mybluethink.in';
                        $cc = '';
                        $emp_pass = rand(1000,10000);
                        $id = User::create([
                            'name' => $importData[$counter-1]['employee_name'],
                            'employee_code' => $importData[$counter-1]['employee_id'],
                            'role' => $depId[0]->id,
                            'email' => $importData[$counter-1]['emp_official_email'],
                            'password'=>\Hash::make($emp_pass)
                        ])->id;
                        $mailflag = true;
                        if($mailflag){
                            $replaceName = str_replace("{{##NAME$}}",$importData[$counter-1]['employee_name'],$emailTemplate[0]->content);
                            $replaceId = str_replace("{{##USERNAME$}}",$importData[$counter-1]['employee_id'],$replaceName);
                            $html = str_replace("{{##PASSWORD$}}",$emp_pass,$replaceId);
                            SendMail::sendMail($html, $emailTemplate[0]->subject, $to, $from, $cc);
                        }
                    }

                    $empReg = DB::select("SELECT * FROM emp_registrations WHERE user_id = ".$id);
                    if(isset($empReg[0]->id) && ($empReg[0]->id)!=''){
                        $empRegister = DB::table('emp_registrations')
                        ->where('user_id', $id)
                        ->update(array(
                            'employee_code' => $importData[$counter-1]['employee_id'],
                            'dob' => $importData[$counter-1]['dob'],
                            'gender' => $importData[$counter-1]['gender'],
                            'job_title' => $importData[$counter-1]['job_title'],
                            'employment_type' => $importData[$counter-1]['employment_type'],
                            'blood_group' => $importData[$counter-1]['blood_group'],
                            'employee_band' => $importData[$counter-1]['employee_band'],
                            'joining_date' => $importData[$counter-1]['doj']
                        ));
                    }else{
                        $empRegister = EmpRegistrations::create([
                            'user_id' => $id,
                            'employee_code' => $importData[$counter-1]['employee_id'],
                            'dob' => $importData[$counter-1]['dob'],
                            'gender' => $importData[$counter-1]['gender'],
                            'job_title' => $importData[$counter-1]['job_title'],
                            'employment_type' => $importData[$counter-1]['employment_type'],
                            'blood_group' => $importData[$counter-1]['blood_group'],
                            'employee_band' => $importData[$counter-1]['employee_band'],
                            'joining_date' => $importData[$counter-1]['doj']
                        ]);
                    }

                    $empAddress = DB::select("SELECT * FROM emp_addresses WHERE user_id = ".$id);
                    if(isset($empAddress[0]->id) && ($empAddress[0]->id)!=''){
                        $empAddress = DB::table('emp_addresses')
                        ->where('user_id', $id)
                        ->update(array(
                            'street' => $importData[$counter-1]['current_street'],
                            'city' => $importData[$counter-1]['current_city'],
                            'state' => $importData[$counter-1]['current_state'],
                            'country' => $importData[$counter-1]['current_country'],
                            'pincode' => $importData[$counter-1]['current_pincode']
                        ));
                    }else{
                        $empAddress = EmpAddress::create([
                            'user_id' => $id,
                            'street' => $importData[$counter-1]['current_street'],
                            'city' => $importData[$counter-1]['current_city'],
                            'state' => $importData[$counter-1]['current_state'],
                            'country' => $importData[$counter-1]['current_country'],
                            'pincode' => $importData[$counter-1]['current_pincode']
                        ]);
                    }

                    $empPerAddress = DB::select("SELECT * FROM emp_per_address WHERE user_id = ".$id);
                    if(isset($empPerAddress[0]->id) && ($empPerAddress[0]->id)!=''){
                        $empPerAddress = DB::table('emp_per_address')
                        ->where('user_id', $id)
                        ->update(array(
                            'p_street' => $importData[$counter-1]['permanent_street'],
                            'p_city' => $importData[$counter-1]['permanent_city'],
                            'p_state' => $importData[$counter-1]['permanent_state'],
                            'p_country' => $importData[$counter-1]['permanent_country'],
                            'p_pincode' => $importData[$counter-1]['permanent_pincode']
                        ));
                    }else{
                        $empPerAddress = EmpPerAddress::create([
                            'user_id' => $id,
                            'p_street' => $importData[$counter-1]['permanent_street'],
                            'p_city' => $importData[$counter-1]['permanent_city'],
                            'p_state' => $importData[$counter-1]['permanent_state'],
                            'p_country' => $importData[$counter-1]['permanent_country'],
                            'p_pincode' => $importData[$counter-1]['permanent_pincode']
                        ]);
                    }

                    $empFamily = DB::select("SELECT * FROM emp_family_details WHERE user_id = ".$id);
                    if(isset($empFamily[0]->id) && ($empFamily[0]->id)!=''){
                        $empFamily = DB::table('emp_family_details')
                        ->where('user_id', $id)
                        ->update(array(
                            'father_name' => $importData[$counter-1]['father_name'],
                            'mother_name' => $importData[$counter-1]['mother_name'],
                            'spouse_name' => $importData[$counter-1]['spouse_name'],
                            'number_type' => '1',
                            'contact_number' => $importData[$counter-1]['father/spouse_contact_no']
                        ));
                    }else{
                        $empFamily = EmpFamilyDetail::create([
                            'user_id' => $id,
                            'father_name' => $importData[$counter-1]['father_name'],
                            'mother_name' => $importData[$counter-1]['mother_name'],
                            'spouse_name' => $importData[$counter-1]['spouse_name'],
                            'number_type' => '1',
                            'contact_number' => $importData[$counter-1]['father/spouse_contact_no']
                        ]);
                    }

                    $empCommunicat = DB::select("SELECT * FROM emp_communications WHERE user_id = ".$id);
                    if(isset($empCommunicat[0]->id) && ($empCommunicat[0]->id)!=''){
                        $empCommunicat = DB::table('emp_communications')
                        ->where('user_id', $id)
                        ->update(array(
                            'mobile_number' => $importData[$counter-1]['emp_contact_no'],
                            'company_email_id' => $importData[$counter-1]['emp_official_email'],
                            'email_id' => $importData[$counter-1]['emp_personal_email']
                        ));
                    }else{
                        $empCommunicat = EmpCommunication::create([
                            'user_id' => $id,
                            'mobile_number' => $importData[$counter-1]['emp_contact_no'],
                            'company_email_id' => $importData[$counter-1]['emp_official_email'],
                            'email_id' => $importData[$counter-1]['emp_personal_email']
                        ]);
                    }

                    $prevEmployment = DB::select("SELECT * FROM emp_prev_employments WHERE user_id = ".$id);
                    if(isset($prevEmployment[0]->id) && ($prevEmployment[0]->id)!=''){
                        $prevEmployment = DB::table('emp_prev_employments')
                        ->where('user_id', $id)
                        ->update(array(
                            'start_date' => json_encode([$importData[$counter-1]['from']]),
                            'end_date' => json_encode([$importData[$counter-1]['till']]),
                            'company_name' => json_encode([$importData[$counter-1]['last_employer']]),
                            'role' => json_encode(['']),
                            'company_emp_ref_name' => json_encode(['']),
                            'company_emp_ref_email' => json_encode(['']),
                            'company_emp_ref_mobile' => json_encode(['']),
                            'company_emp_ref_role' => json_encode([''])
                        ));
                    }else{
                        $prevEmployment = EmpPrevEmployment::create([
                            'user_id' => $id,
                            'start_date' => json_encode([$importData[$counter-1]['from']]),
                            'end_date' => json_encode([$importData[$counter-1]['till']]),
                            'company_name' => json_encode([$importData[$counter-1]['last_employer']]),
                            'role' => json_encode(['']),
                            'company_emp_ref_name' => json_encode(['']),
                            'company_emp_ref_email' => json_encode(['']),
                            'company_emp_ref_mobile' => json_encode(['']),
                            'company_emp_ref_role' => json_encode([''])
                        ]);
                    }

                    $empSkills = DB::select("SELECT * FROM skills WHERE user_id = ".$id);
                    if(isset($empSkills[0]->id) && ($empSkills[0]->id)!=''){
                        $empSkills = DB::table('skills')
                        ->where('user_id', $id)
                        ->update(array(
                            'skill_name' => $importData[$counter-1]['skills']
                        ));
                    }else{
                        $empSkills = Skills::create([
                            'user_id' => $id,
                            'skill_name' => $importData[$counter-1]['skills']
                        ]);
                    }

                    $empAccountDetails = DB::select("SELECT * FROM emp_account_details WHERE user_id = ".$id);
                    if(isset($empAccountDetails[0]->id) && ($empAccountDetails[0]->id)!=''){
                        $empAccountDetails = DB::table('emp_account_details')
                        ->where('user_id', $id)
                        ->update(array(
                            'bank_name' => $importData[$counter-1]['bank_name'],
                            'acc_no' => $importData[$counter-1]['account_number'],
                            'ifsc' => $importData[$counter-1]['ifsc_code']
                        ));
                    }else{
                        $empAccountDetails = EmpAccountDetails::create([
                            'user_id' => $id,
                            'bank_name' => $importData[$counter-1]['bank_name'],
                            'acc_no' => $importData[$counter-1]['account_number'],
                            'ifsc' => $importData[$counter-1]['ifsc_code']
                        ]);
                    }

                    $empAccount = DB::select("SELECT * FROM emp_accounts WHERE user_id = ".$id);
                    if(isset($empAccount[0]->id) && ($empAccount[0]->id)!=''){
                        $empAccount = DB::table('emp_accounts')
                        ->where('user_id', $id)
                        ->update(array(
                            'addhar_number' => $importData[$counter-1]['aadhaar_number'],
                            'pan_number' => $importData[$counter-1]['pan_number']
                        ));
                    }else{
                        $empAccount = EmpAccount::create([
                            'user_id' => $id,
                            'addhar_number' => $importData[$counter-1]['aadhaar_number'],
                            'pan_number' => $importData[$counter-1]['pan_number']
                        ]);
                    }
                }else{
                    echo "Invalid department name ".$importData[$counter-1]['department'];
                }
            }
            $counter++;
        }
        fclose($file);
        return redirect()->route('empimport')->with('success','Employee Imported Successfully.');
    }
}
