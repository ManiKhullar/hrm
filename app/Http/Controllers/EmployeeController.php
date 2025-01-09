<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmpAddress;
use App\Models\EmpPerAddress;
use App\Models\EmpCommunication;
use App\Models\EmpAccountDetails;
use App\Models\EmpEducation;
use App\Models\EmpFamilyDetail;
use App\Models\EmpPrevEmployment;
use App\Models\EmpRegistrations;
use App\Models\User;
use App\Models\CityState;
use DB;
use App\Models\EmpAccount;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ImageUploadRequest;
use File;
use Illuminate\Support\Facades\Auth;
use App\Http\Helper\SendMail;
use App\Http\Helper\CityStatelist;
use App\Http\Controllers\Route;

class EmployeeController extends Controller
{
    public function employee_add()
    {
        $departments = DB::select('select * from department WHERE status=1');
        $technology = DB::select("select * from emp_technology WHERE status='1'");
        $empShift = DB::select("select * from emp_shift WHERE status='Enable'");
        $state = DB::select('select DISTINCT state from city_states');
        return view('employee/employeeadd',[
            'state'=> $state,
            'empShift'=> $empShift,
            'technology'=> $technology,
            'departments'=> $departments
        ]);
    }

    public function city_name(Request $request)
    {
        $city = DB::select("select city from city_states where state='$request->state'");
        return response()->json($city); 
    }

    public function employee_save(Request $request)
    {
        $request->validate([
        'first_name' => 'required',
        'last_name' => 'required',
        'email' => 'required|unique:users|email',
        'dob' => 'before:today',
        'department_role' => 'required',
        'job_title' => 'required',
        'employee_band' => 'required',
        'emp_shift' => 'required',
       // 'bank_name' => 'required',
        //'acc_no' => 'required',
       // 'ifsc' => 'required',
       // 'salary' => 'required',
        'technology' => 'required',
        // 'profile_pic'=>'nullable|mimes:jpeg,png,jpg',
        // 'addhar_doc_file'=>'nullable|mimes:pdf',
        // 'pan_doc_file'=>'nullable|mimes:pdf',
        // 'offer_letter'=>'nullable|mimes:pdf',
        // 'relieving_latter'=>'nullable|mimes:pdf',
        // 'resignation_letter'=>'nullable|mimes:pdf',
        // 'appointment_latter'=>'nullable|mimes:pdf',
        // 'bank_statment'=>'nullable|mimes:pdf',
        // 'salary_slip1'=>'nullable|mimes:pdf',
        // 'salary_slip2'=>'nullable|mimes:pdf',
        // 'salary_slip3'=>'nullable|mimes:pdf',
        // 'highschool'=>'nullable|mimes:pdf',
        // 'intermediate'=>'nullable|mimes:pdf',
        // 'graduation'=>'nullable|mimes:pdf',
        // 'post_graduation'=>'nullable|mimes:pdf'
        ]);

    //try{
        
        $employeeData = DB::select("select * from emp_registrations where employment_type='$request->employment_type' order by employee_code desc limit 1");
        $emp_code = $employeeData[0]->employee_code;
        if (str_contains($emp_code, "BT1110")) {
            $parts = str_split($emp_code, 6);
            $parts1 = str_pad(($parts[1]+1), 4, '0', STR_PAD_LEFT);
            $emp_code = $parts[0].($parts1);
        } elseif(str_contains($emp_code, "BTITEXT")) {
            $parts = str_split($emp_code, 7);
            $parts1 = str_pad(($parts[1]+1), 3, '0', STR_PAD_LEFT);
            $emp_code = $parts[0].($parts1);
        }
        $employee_code = $emp_code;
        $file_path = public_path('doc_images').$employee_code;
        if(!is_dir($file_path)){
            File::makeDirectory($file_path, 0775);
        }

        //$emp_pass = rand(1000,10000);
        $emp_pass = 'admin@12345';
        $id = User::create([
           'name'=>$request->first_name.' '.$request->last_name,
           'employee_code'=>$employee_code,
           'role'=> $request->department_role,
           'email'=>$request->email,
           'emp_shift_id'=>$request->emp_shift,
           'technology_id'=>$request->technology,
           'password'=>\Hash::make($emp_pass),
           'is_paid' => $request->is_paid
        ])->id;

        EmpRegistrations::create([
            'user_id'=>$id,
            'employee_code'=>$employee_code,
            'dob'=>$request->dob,
            'gender'=>$request->gender,
            'job_title'=>$request->job_title,
            'employment_type'=>$request->employment_type,
            'blood_group'=>$request->blood_group,
            'employee_band'=>$request->employee_band,
            'joining_date'=>$request->doj
        ]);
       
        $profile_pic = "";
        if ($request->has('profile_pic')) {
            $profile_pic = time() . 'profile_pic.' .$request->profile_pic->extension();
            $request->profile_pic->move($file_path, $profile_pic);
        }
        $addhar_doc_file = "";
        if ($request->has('addhar_doc_file')) {
            $addhar_doc_file = time() . 'addhar_doc_file.' .$request->addhar_doc_file->extension();
            $request->addhar_doc_file->move($file_path, $addhar_doc_file);
        }
        $pan_doc_file = "";
        if ($request->has('pan_doc_file')) {
            $pan_doc_file = time() . 'pan_doc_file.' .$request->pan_doc_file->extension();
            $request->pan_doc_file->move($file_path, $pan_doc_file);
        }
        $offer_letter = "";
        if ($request->has('offer_letter')) {
            $offer_letter = time() . 'offer_letter.' .$request->offer_letter->extension();
            $request->offer_letter->move($file_path, $offer_letter);
        }
        $relieving_latter = "";
        if ($request->has('relieving_latter')) {
            $relieving_latter = time() . 'relieving_latter.' .$request->relieving_latter->extension();
            $request->relieving_latter->move($file_path, $relieving_latter);
        }

        $resignation_letter = "";
        if ($request->has('resignation_letter')) {
            $resignation_letter = time() . 'resignation_letter.' .$request->resignation_letter->extension();
            $request->resignation_letter->move($file_path, $resignation_letter);
        }

        $appointment_latter = "";
        if ($request->has('appointment_latter')) {
            $appointment_latter = time() . 'appointment_latter.' .$request->appointment_latter->extension();
            $request->appointment_latter->move($file_path, $appointment_latter);
        }
        $bank_statment = "";
        if ($request->has('bank_statment')) {
            $bank_statment = time() . 'bank_statment.' .$request->bank_statment->extension();
            $request->bank_statment->move($file_path, $bank_statment);
        }
        $salary_slip1 = "";
        if ($request->has('salary_slip1')) {
            $salary_slip1 = time() . 'salary_slip1.' .$request->salary_slip1->extension();
            $request->salary_slip1->move($file_path, $salary_slip1);
        }
        $salary_slip2 = "";
        if ($request->has('salary_slip2')) {
            $salary_slip2 = time() . 'salary_slip2.' .$request->salary_slip2->extension();
            $request->salary_slip2->move($file_path, $salary_slip2);
        }
        $salary_slip3 = "";
        if ($request->has('salary_slip3')) {
            $salary_slip3 = time() . 'salary_slip3.' .$request->salary_slip3->extension();
            $request->salary_slip3->move($file_path, $salary_slip3);
        }

        $highschool = "";
        if ($request->has('high_marksheet')) {
            $highschool = time() . 'high_marksheet.' .$request->high_marksheet->extension();
            $request->high_marksheet->move($file_path, $highschool);
        }
        $intermediate = "";
        if ($request->has('inter_marksheet')) {
            $intermediate = time() . 'intermediate.' .$request->inter_marksheet->extension();
            $request->inter_marksheet->move($file_path, $intermediate);
        }
        $graduation = "";
        if ($request->has('graducation_marksheet')) {
            $graduation = time() . 'graduation.' .$request->graducation_marksheet->extension();
            $request->graducation_marksheet->move($file_path, $graduation);
        }
        $post_graduation = "";
        if ($request->has('post_graduation_marksheet')) {
            $post_graduation = time() . 'post_graduation_marksheet.' .$request->post_graduation_marksheet->extension();
            $request->post_graduation_marksheet->move($file_path, $post_graduation);
        }
      
        EmpAccount::create([
            'user_id'=>$id,
            'profile_pic'=>$profile_pic,
            'addhar_number'=>$request->addhar_number,
            'addhar_doc_file'=>$addhar_doc_file,
            'pan_number'=>$request->pan_number,
            'pan_doc_file'=>$pan_doc_file,
            'offer_letter'=>$offer_letter,
            'relieving_latter'=>$relieving_latter,
            'resignation_letter'=>$resignation_letter,
            'appointment_latter'=>$appointment_latter,
            'bank_statment'=>$bank_statment,
            'salary_slip1'=>$salary_slip1,
            'salary_slip2'=>$salary_slip2,
            'salary_slip3'=>$salary_slip3
        ]);

        EmpAddress::create([
            'user_id'=>$id,
            'street'=>$request->street,
            'city'=>$request->city,
            'state'=>$request->state,
            'country'=>$request->country,
            'pincode'=>$request->pincode,
        ]);

        EmpPerAddress::create([
            'user_id'=>$id,
            'p_street'=>$request->p_street,
            'p_city'=>$request->p_city,
            'p_state'=>$request->p_state,
            'p_country'=>$request->p_country,
            'p_pincode'=>$request->p_pincode,
        ]);

        EmpAccountDetails::create([
            'user_id'=>$id,
            'bank_name'=>$request->bank_name,
            'acc_no'=>$request->acc_no,
            'ifsc'=>$request->ifsc,
            'salary'=>$request->salary
        ]);

        EmpCommunication::create([
            'user_id'=>$id,
            'mobile_number'=>$request->mobile_number,
            'company_email_id'=>$request->company_email_id,
            'internal_email_id'=>$request->email_id,
            'email_id'=>$request->client_email_id,
        ]);
       
        EmpEducation::create([
            'user_id'=>$id,
            'highschool'=>$highschool,
            'intermediate'=>$intermediate,
            'graduation'=>$graduation,
            'post_graduation'=>$post_graduation
        ]);


        EmpFamilyDetail::create([
            'user_id'=>$id,
            'father_name'=>$request->father_name,
            'mother_name'=>$request->mother_name,
            'spouse_name'=>$request->spouse_name,
            'number_type'=>$request->number_type,
            'contact_number'=>$request->contact_number
        ]);

        EmpPrevEmployment::create([
            'user_id'=>$id,
            'start_date'=>json_encode($request->start_date),
            'end_date'=>json_encode($request->end_date),
            'company_name'=>json_encode($request->company_name),
            'role'=>json_encode($request->role),
            'company_emp_ref_name'=>json_encode($request->company_emp_ref_name),
            'company_emp_ref_email'=>json_encode($request->company_emp_ref_email),
            'company_emp_ref_mobile'=>json_encode($request->company_emp_ref_mobile),
            'company_emp_ref_role'=>json_encode($request->company_emp_ref_role)
        ]);

        $empTechnology = DB::select("select tech_name from emp_technology where id = $request->technology");
        $data = [
            'emp_id' => $employee_code,
            'name' => $request->first_name.' '.$request->last_name,
            'technology' => $empTechnology[0]->tech_name,
            'email' => $request->email,
            'mobile' => 'NA'
        ];
        $post = json_encode($data,true);

        $ch = curl_init('http://itsd.bluethinkinc.com/api/saveEmp');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Connection: Keep-Alive'
            ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        $response = curl_exec($ch);
        curl_close($ch);

        $to = $request->email;
        $from = 'noreply@mybluethink.in';
        $cc = '';
        $name = $request->first_name.' '.$request->last_name;
        
        $emailTemplate = DB::select("SELECT * FROM email_templates WHERE type = 'user_registration' AND status = '1'");
        if(!empty($emailTemplate[0]))
        {    
            $replaceName = str_replace("{{##NAME$}}",$name,$emailTemplate[0]->content);
            $replaceId = str_replace("{{##USERNAME$}}",$employee_code,$replaceName);
            $html = str_replace("{{##PASSWORD$}}",$emp_pass,$replaceId);
            SendMail::sendMail($html, $emailTemplate[0]->subject, $to, $from, $cc);
        }   
        return redirect('employee_list');
    }

    public function employee_list()
    {
        // $employeeData = DB::select('select *  from emp_registrations left join users on emp_registrations.user_id=users.id');
        $employeeData = DB::table("emp_registrations")
                    ->leftjoin("users", "emp_registrations.user_id", '=', "users.id")
                    ->leftjoin("emp_accounts", "emp_accounts.user_id", '=', "users.id")
                    ->orderBy('id','DESC')
                    ->paginate(10,array('emp_registrations.status','emp_registrations.user_id as id','emp_registrations.gender','emp_registrations.joining_date','emp_registrations.accept_policy','emp_accounts.profile_pic','users.email', 'users.employee_code','users.email','users.name'));

        return view('employee/employeelist',[
            'employeeData'=> $employeeData
        ]);
    }

    public function empstatus_change(Request $request, $id, $status)
    {
        $empRegistration = EmpRegistrations::where('user_id', $id)->firstOrFail();
        $empRegistration->update(['status' => $status]);

        return redirect()->route('employee_list')->with('success','Employee Status Change Successfully');
    }

    public function employee_delete($id)
    {
        $users = DB::delete('delete from users where id='.$id);
        $emp_addresses = DB::delete('delete from emp_addresses where user_id='.$id);
        $emp_communications = DB::delete('delete from emp_communications where user_id='.$id);
        $emp_education = DB::delete('delete from emp_education where user_id='.$id);
        $emp_family_details = DB::delete('delete from emp_family_details where user_id='.$id);
        $emp_account_details = DB::delete('delete from emp_account_details where user_id='.$id);
        $emp_prev_employments = DB::delete('delete from emp_prev_employments where user_id='.$id);
        $emp_registrations = DB::delete('delete from emp_registrations where user_id='.$id);

        return redirect()->route('employee_list')->with('success','Employee Deleted Successfully'); 
    }

    public function employee_import()
    {
        return view('employee_import');
    }

    public function employee_import_save(Request $request)
    {
        $csv_file = time() . '.' .$request->csv_file->extension();
        $request->csv_file->move(public_path('doc_images'), $csv_file);
        $filename = public_path('doc_images/'.$csv_file);
        $file = fopen($filename, "r");
        $all_data = array();
        echo "<pre>";
        while ( ($data = fgetcsv($file, 200, ",")) !==FALSE ) {
            print_r($data);
        }
        exit;
        // $profile_pic = time() . '.' .$request->profile_pic->extension();
        // $request->profile_pic->move(public_path('doc_images'), $profile_pic);
        // $row = 1;
        // if (($handle = fopen("test.csv", "r")) !== FALSE) {
        // while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        //     $num = count($data);
        //     echo "<p> $num fields in line $row: <br /></p>\n";
        //     $row++;
        //     for ($c=0; $c < $num; $c++) {
        //         echo $data[$c] . "<br />\n";
        //     }
        // }
        // fclose($handle);
        // }
    }

    public function employee_export()
    {
        echo "bbbbbb";exit;
    }

    public function employee_view($id)
    {
        $departments = DB::select('select * from department WHERE status=1');
        $technology = DB::select("select * from emp_technology WHERE status='1'");
        $empShift = DB::select("select * from emp_shift WHERE status='Enable'");
        $state = DB::select('select DISTINCT state from city_states');
        $employeeData = DB::select('select *,users.role as emp_role from users 
        left join  emp_shift on emp_shift.id=users.emp_shift_id
        left join  emp_technology on emp_technology.id=users.technology_id
        left join  emp_accounts on users.id=emp_accounts.user_id
        left join  emp_account_details on users.id=emp_account_details.user_id 
        left join  emp_addresses on users.id=emp_addresses.user_id
        left join  emp_per_address on users.id=emp_per_address.user_id
        left join  emp_communications on users.id=emp_communications.user_id
        left join  emp_family_details on users.id=emp_family_details.user_id
        left join  emp_prev_employments on users.id=emp_prev_employments.user_id
        left join  emp_education on users.id=emp_education.user_id
        left join  emp_registrations on users.id=emp_registrations.user_id where users.id='.$id);

        //echo count(json_decode($employeeData[0]->company_name));exit;
        $previousEmployemt = array();
        if(isset($employeeData[0]) && $employeeData[0]->company_name!=''){
            for($i=0; $i<count(json_decode($employeeData[0]->company_name)); $i++){
                $previousEmployemt[] = array(
                    "company_name"=>json_decode($employeeData[0]->company_name)[$i],
                    "start_date"=>json_decode($employeeData[0]->start_date)[$i],
                    "end_date"=>json_decode($employeeData[0]->end_date)[$i],
                    "role"=>json_decode($employeeData[0]->role)[$i],
                    "company_emp_ref_name"=>json_decode($employeeData[0]->company_emp_ref_name)[$i],
                    "company_emp_ref_email"=>json_decode($employeeData[0]->company_emp_ref_email)[$i],
                    "company_emp_ref_mobile"=>json_decode($employeeData[0]->company_emp_ref_mobile)[$i],
                    "company_emp_ref_role"=>json_decode($employeeData[0]->company_emp_ref_role)[$i]
                ); 
            }
        }
        $employeeData['previous_employee'] = $previousEmployemt;  
        
        return view('employee/employeeview',[
            'employeeData'=> $employeeData,
            'state'=> $state,
            'departments'=>$departments,
            'technology'=>$technology,
            'empShift'=>$empShift,
            'cityCollection'=>CityStatelist::allCityCollection()
        ]);
    }
    
    public function employeeRegister_update(Request $request)
    {
        try{
            $employeeDatas = DB::select("select * from emp_registrations where user_id=$request->user_id order by employee_code desc limit 1");
            $emp_codes = $employeeDatas[0]->employee_code;
            if(($request->employment_type == 'fte') && (str_contains($emp_codes, "BT1110") == false)){
                $employeeData = DB::select("select * from emp_registrations where employment_type='$request->employment_type' order by employee_code desc limit 1");
                $emp_code = $employeeData[0]->employee_code;
                if (str_contains($emp_code, "BT1110")) {
                    $parts = str_split($emp_code, 6);
                    $parts1 = str_pad(($parts[1]+1), 4, '0', STR_PAD_LEFT);
                    $emp_code = $parts[0].($parts1);
                    $emp_registrations = DB::table('emp_registrations')
                        ->where('user_id', $request->user_id)
                        ->update(array(
                            'employee_code' => $emp_code
                        ));
                    $users = DB::table('users')
                        ->where('id', $request->user_id)
                        ->update(array(
                            'employee_code' => $emp_code,'is_paid'=>$request->is_paid
                        ));
                }
            }
            $emp_registrations = DB::table('emp_registrations')
                ->where('user_id', $request->user_id)
                ->update(array(
                    'dob'=>$request->dob,
                    'gender'=>$request->gender,
                    'job_title'=>$request->job_title,
                    'employment_type'=>$request->employment_type,
                    'blood_group'=>$request->blood_group,
                    'employee_band'=>$request->employee_band,
                    'joining_date'=>$request->doj,
                ));
            $users = DB::table('users')
                ->where('id', $request->user_id)
                ->update(array(
                    'name' => $request->name,
                    'role' => $request->department_role,
                    'email' => $request->email,
                    'emp_shift_id' => $request->emp_shift,
                    'technology_id' => $request->technology,
                    'is_paid'=>$request->is_paid
                ));
            $emp_account_details = DB::table('emp_account_details')
                ->where('user_id', $request->user_id)
                ->update(array(
                    'salary'=>$request->employee_salary
                ));

            $empTechnology = DB::select("select tech_name from emp_technology where id = $request->technology");
            $data = [
                'emp_id' => $emp_codes,
                'name' => $request->name,
                'technology' => $empTechnology[0]->tech_name,
                'email' => $request->email,
                'mobile' => 'NA'
            ];
            $post = json_encode($data,true);
    
            $ch = curl_init('http://itsd.bluethinkinc.com/api/saveEmp');
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Connection: Keep-Alive'
                ));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            $response = curl_exec($ch);
            curl_close($ch);

            return json_encode([
                'success'=>true,
                'userid'=>$request->user_id
            ]);
        } catch (\Exception $e) {
            echo  $e->getMessage();exit;
        }
    }

    public function employeeAddress_update(Request $request)
    {
        $empAddress = DB::table('emp_addresses')
            ->where('user_id', $request->user_id)
            ->update(array(
                'street'=>$request->street,
                'city'=>$request->city,
                'state'=>$request->state,
                'country'=>$request->country,
                'pincode'=>$request->pincode,
            ));
        return json_encode([
            'success'=>true,
            'userid'=>$request->user_id
        ]);
    }

    public function employeePerAddress_update(Request $request)
    {
        $empPerAddress = DB::table('emp_per_address')
            ->where('user_id', $request->user_id)
            ->update(array(
                'p_street'=>$request->p_street,
                'p_city'=>$request->p_city,
                'p_state'=>$request->p_state,
                'p_country'=>$request->p_country,
                'p_pincode'=>$request->p_pincode,
            ));
        return json_encode([
            'success'=>true,
            'userid'=>$request->user_id
        ]);
    }

    public function employeeAccountDetails_Update(Request $request)
    {
        $empAccountDetails = DB::table('emp_account_details')
            ->where('user_id', $request->user_id)
            ->update(array(
                'bank_name'=>$request->bank_name,
                'acc_no'=>$request->acc_no,
                'ifsc'=>$request->ifsc,
                'salary'=>$request->salary
            ));
        return json_encode([
            'success'=>true,
            'userid'=>$request->user_id
        ]);
    }

    public function employeeFamily_Update(Request $request)
    {
        $empFamily = DB::table('emp_family_details')
            ->where('user_id', $request->user_id)
            ->update(array(
                'father_name'=>$request->father_name,
                'mother_name'=>$request->mother_name,
                'spouse_name'=>$request->spouse_name,
                'number_type'=>$request->number_type,
                'contact_number'=>$request->contact_number
            ));
        return json_encode([
            'success'=>true,
            'userid'=>$request->user_id
        ]);
    }

    public function employeeCommunication_Update(Request $request)
    {
        $empCommunication = DB::table('emp_communications')
            ->where('user_id', $request->user_id)
            ->update(array(
                'mobile_number'=>$request->mobile_number,
                'company_email_id'=>$request->company_email_id,
                'internal_email_id'=>$request->internal_email_id,
                'email_id'=>$request->email_id
            ));
        return json_encode([
            'success'=>true,
            'userid'=>$request->user_id
        ]);
    }

    public function employeeEduaction_Update(Request $request)
    {
        $employee_code =  DB::select("select employee_code from users where id=".$request->user_id);
        $file_path = public_path('doc_images').$employee_code[0]->employee_code;
        if(!is_dir($file_path)){
            File::makeDirectory($file_path, 0775);
        }

        $highschool = "";
        $userData = [];
        if ($request->has('high_marksheet')) {
            $highschool = time() . 'high_marksheet.' .$request->high_marksheet->extension();
            $request->high_marksheet->move($file_path, $highschool);
            $userData['highschool'] = $highschool;
        }
        $intermediate = "";
        if ($request->has('inter_marksheet')) {
            $intermediate = time() . 'intermediate.' .$request->inter_marksheet->extension();
            $request->inter_marksheet->move($file_path, $intermediate);
            $userData['intermediate'] = $intermediate;
        }
        $graduation = "";
        if ($request->has('graducation_marksheet')) {
            $graduation = time() . 'graduation.' .$request->graducation_marksheet->extension();
            $request->graducation_marksheet->move($file_path, $graduation);
            $userData['graduation'] = $graduation;
        }
        $post_graduation = "";
        if ($request->has('post_graduation_marksheet')) {
            $post_graduation = time().'post_graduation_marksheet.'.$request->post_graduation_marksheet->extension();
            $request->post_graduation_marksheet->move($file_path, $post_graduation);
            $userData['post_graduation'] = $post_graduation;
        }

        $empData =  DB::select("select * from emp_education where user_id=".$request->user_id);

        if(!empty($empData)){
            $empEduaction = DB::table('emp_education')
            ->where('user_id', $request->user_id)
            ->update($userData);
        }else{
            $userData['user_id'] = $request->user_id;
            EmpEducation::create($userData);
        }
        
        return json_encode([
            'success'=>true,
            'userid'=>$request->user_id
        ]);
    }

    public function employeeAccount_Update(Request $request)
    {
        $employee_code =  DB::select("select employee_code from users where id=".$request->user_id);
        $file_path = public_path('doc_images').$employee_code[0]->employee_code;
        if(!is_dir($file_path)){
            File::makeDirectory($file_path, 0775);
        }

        $profile_pic = "";
        $userData = [];
        if ($request->has('profile_pic')) {
            $profile_pic = time() . 'profile_pic.' .$request->profile_pic->extension();
            $request->profile_pic->move($file_path, $profile_pic);
            $userData['profile_pic'] = $profile_pic;
        }
        $addhar_doc_file = "";
        if ($request->has('addhar_doc_file')) {
            $addhar_doc_file = time() . 'addhar_doc_file.' .$request->addhar_doc_file->extension();
            $request->addhar_doc_file->move($file_path, $addhar_doc_file);
            $userData['addhar_doc_file'] = $addhar_doc_file;
        }
        $pan_doc_file = "";
        if ($request->has('pan_doc_file')) {
            $pan_doc_file = time() . 'pan_doc_file.' .$request->pan_doc_file->extension();
            $request->pan_doc_file->move($file_path, $pan_doc_file);
            $userData['pan_doc_file'] = $pan_doc_file;
        }
        $offer_letter = "";
        if ($request->has('offer_letter')) {
            $offer_letter = time() . 'offer_letter.' .$request->offer_letter->extension();
            $request->offer_letter->move($file_path, $offer_letter);
            $userData['offer_letter'] = $offer_letter;
        }
        $relieving_latter = "";
        if ($request->has('relieving_latter')) {
            $relieving_latter = time() . 'relieving_latter.' .$request->relieving_latter->extension();
            $request->relieving_latter->move($file_path, $relieving_latter);
            $userData['relieving_latter'] = $relieving_latter;
        }
        $resignation_letter = "";
        if ($request->has('resignation_letter')) {
            $resignation_letter = time() . 'resignation_letter.' .$request->resignation_letter->extension();
            $request->resignation_letter->move($file_path, $resignation_letter);
            $userData['resignation_letter'] = $resignation_letter;
        }
        $appointment_latter = "";
        if ($request->has('appointment_latter')) {
            $appointment_latter = time() . 'appointment_latter.' .$request->appointment_latter->extension();
            $request->appointment_latter->move($file_path, $appointment_latter);
            $userData['appointment_latter'] = $appointment_latter;
        }
        $bank_statment = "";
        if ($request->has('bank_statment')) {
            $bank_statment = time() . 'bank_statment.' .$request->bank_statment->extension();
            $request->bank_statment->move($file_path, $bank_statment);
            $userData['bank_statment'] = $bank_statment;

        }
        $salary_slip1 = "";
        if ($request->has('salary_slip1')) {
            $salary_slip1 = time() . 'salary_slip1.' .$request->salary_slip1->extension();
            $request->salary_slip1->move($file_path, $salary_slip1);
            $userData['salary_slip1'] = $salary_slip1;
        }
        $salary_slip2 = "";
        if ($request->has('salary_slip2')) {
            $salary_slip2 = time() . 'salary_slip2.' .$request->salary_slip2->extension();
            $request->salary_slip2->move($file_path, $salary_slip2);
            $userData['salary_slip2'] = $salary_slip2;
        }
        $salary_slip3 = "";
        if ($request->has('salary_slip3')) {
            $salary_slip3 = time() . 'salary_slip3.' .$request->salary_slip3->extension();
            $request->salary_slip3->move($file_path, $salary_slip3);
            $userData['salary_slip3'] = $salary_slip3;
        }
        $addhar_number = "";
        if ($request->has('addhar_number')) {
            $userData['addhar_number'] = $request->addhar_number;
        }
        $pan_number = "";
        if ($request->has('pan_number')) {
            $userData['pan_number'] = $request->pan_number;
        }

        $empAccount = DB::table('emp_accounts')
            ->where('user_id', $request->user_id)
            ->update($userData);
        return json_encode([
            'success'=>true,
            'userid'=>$request->user_id
        ]);
    }

    public function employeePrevious_Update(Request $request)
    {
        $empPrevious = DB::table('emp_prev_employments')
            ->where('user_id', $request->user_id)
            ->update(array(
                'start_date'=>json_encode($request->start_date),
                'end_date'=>json_encode($request->end_date),
                'company_name'=>json_encode($request->company_name),
                'role'=>json_encode($request->role),
                'company_emp_ref_name'=>json_encode($request->company_emp_ref_name),
                'company_emp_ref_email'=>json_encode($request->company_emp_ref_email),
                'company_emp_ref_mobile'=>json_encode($request->company_emp_ref_mobile),
                'company_emp_ref_role'=>json_encode($request->company_emp_ref_role)
            ));
        return json_encode([
            'success'=>true,
            'userid'=>$request->user_id
        ]);
    }

    public function view_employee()
    {
        $user_id = Auth::user()->id;
        $state = DB::select('select DISTINCT state from city_states');
        $employeeData = DB::select('select * from users
        left join  emp_shift on emp_shift.id=users.emp_shift_id
        left join  emp_accounts on users.id=emp_accounts.user_id
        left join  emp_account_details on users.id=emp_account_details.user_id 
        left join  emp_addresses on users.id=emp_addresses.user_id
        left join  emp_communications on users.id=emp_communications.user_id
        left join  emp_family_details on users.id=emp_family_details.user_id
        left join  emp_prev_employments on users.id=emp_prev_employments.user_id
        left join  emp_education on users.id=emp_education.user_id
        left join  emp_registrations on users.id=emp_registrations.user_id 
        left join emp_per_address on users.id=emp_per_address.user_id where users.id='.$user_id);

        //echo count(json_decode($employeeData[0]->company_name));exit;
        $previousEmployemt = array();
        if(isset($employeeData[0]) && $employeeData[0]->company_name!=''){
            for($i=0; $i<count(json_decode($employeeData[0]->company_name)); $i++){
                $previousEmployemt[] = array(
                    "company_name"=>json_decode($employeeData[0]->company_name)[$i],
                    "start_date"=>json_decode($employeeData[0]->start_date)[$i],
                    "end_date"=>json_decode($employeeData[0]->end_date)[$i],
                    "role"=>json_decode($employeeData[0]->role)[$i],
                    "company_emp_ref_name"=>json_decode($employeeData[0]->company_emp_ref_name)[$i],
                    "company_emp_ref_email"=>json_decode($employeeData[0]->company_emp_ref_email)[$i],
                    "company_emp_ref_mobile"=>json_decode($employeeData[0]->company_emp_ref_mobile)[$i],
                    "company_emp_ref_role"=>json_decode($employeeData[0]->company_emp_ref_role)[$i]
                ); 
            }
        }
        $employeeData['previous_employee'] = $previousEmployemt;          
        return view('employee/userview',[
            'employeeData'=> $employeeData,
            'state'=> $state,
            'cityCollection'=>CityStatelist::allCityCollection()
        ]);
    }

    public function change_password()
    {
        return view('employee/changepassword');
    }

    public function change_password_save(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required|string',
            'new_password' => 'required|confirmed|min:6|string'
        ]);
        $auth = Auth::user();
	    // The passwords matches
        if (!\Hash::check($request->get('current_password'), $auth->password)) 
        {
            return back()->with('error', "Current Password Is Invalid");
        }
        // Current password and new password same
        if (strcmp($request->get('current_password'), $request->new_password) == 0) 
        {
         return redirect()->back()->with("error", "New Password cannot be same as your current password.");
        }
        $user =  User::find($auth->id);
        $user->password =  \Hash::make($request->new_password);
        $user->save();
        return back()->with('success', "Password Changed Successfully");
    }

    public function employee_filter(Request $request)
    {
        $employeeData =  DB::select("select emp_registrations.status, emp_registrations.accept_policy as accept_policy, emp_registrations.user_id as id,emp_registrations.gender as gender,emp_registrations.joining_date as doj,emp_accounts.profile_pic as profile_pic,users.email as email, users.employee_code as employee_code,users.email,users.name as name 
        from emp_registrations left join users on emp_registrations.user_id=users.id 
        left join emp_accounts on emp_accounts.user_id=users.id 
        where users.name like '%{$request->search}%' OR users.email like '%{$request->search}%' OR emp_registrations.accept_policy like '%{$request->search}%' OR users.employee_code like '%{$request->search}%' order by name ASC"); 

        return json_encode($employeeData);
    }


    public function profile_pic_upload(Request $request)
    {
        $employee_code = Auth::user()->employee_code;
        $user_id =Auth::user()->id;
        $file_path = public_path('doc_images').$employee_code;
        if(!is_dir($file_path)){
            File::makeDirectory($file_path, 0775);
        }
        if(isset($_FILES['file']['name'])){
            $userData = [];
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $file_name=time().'profile_pic'.'.'.$ext;   
            move_uploaded_file($_FILES['file']["tmp_name"],$file_path.'/'.$file_name);
            $userData['profile_pic'] = $file_name;
            $empAccount = DB::table('emp_accounts')
                ->where('user_id', $user_id)
                ->update($userData);
        }
        
        $image_Url = url('doc_images'.$employee_code.'/'.$file_name);
        return json_encode([
            'success'=>true,
            'userid'=>$user_id,
            'image_path'=>$image_Url
        ]);
    }

}
