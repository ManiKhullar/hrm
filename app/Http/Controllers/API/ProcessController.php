<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
class ProcessController extends Controller
{
    public function getList(Request $request)
    {

        $processdata = DB::select("select process.id,process.client,process.client_poc,process.client_email,process.rate,process.client_cell,process.source,process.status,process.candidate_id,process.consultant ,process.contact_id,process.created_by,process.creation_date,process.properties,
        
        sub_process.id as sub_id,sub_process.client_email as sub_client_email,sub_process.client_cell as sub_client_cell,sub_process.interview_date as sub_interview_date,sub_process.interview_panel as sub_interview_panel,sub_process.time as sub_time,

        contact.id as cont_id,contact.first_name as cont_first_name,contact.last_name as cont_last_name,contact.email as cont_email,contact.mobile as cont_mobile

        from process 
        left join sub_process on sub_process.process_id=process.id
        left join contact on contact.id=process.contact_id

        where process.client like '%{$request->search}%'
         OR process.client_poc like '%{$request->search}%'
         OR process.client_email like '%{$request->search}%'
         OR process.client_cell like '%{$request->search}%'
         OR process.source like '%{$request->search}%'
         OR sub_process.client_email like '%{$request->search}%'
         OR sub_process.client_cell like '%{$request->search}%'
         OR sub_process.interview_panel like '%{$request->search}%'
         OR sub_process.interview_date like '%{$request->search}%'
         OR sub_process.time like '%{$request->search}%'
         OR contact.first_name like '%{$request->search}%'
         OR contact.last_name like '%{$request->search}%'
         OR contact.email like '%{$request->search}%'
         OR contact.mobile like '%{$request->search}%'
        ");
        $result = array();
        foreach($processdata as $process){
            $result['process'][] = array(
                "id"=> $process->id,
                "client"=> $process->client,
                "clientPoc"=> $process->client_poc,
                "clientEmail"=> $process->client_email,
                "clientCell"=> $process->client_cell,
                "source"=> $process->source ,
                "rate"=> $process->rate,
                "status"=> $process->status,
                "profileCode"=> $process->candidate_id,
                "consultant"=> $process->consultant,
                "contactId"=> $process->contact_id,
                "createdBy"=> $process->created_by,
                "creationDate"=> $process->creation_date,
                "properties"=> $process->properties
            );
           
            $result['sub_process'][] = array(
                "id"=> $process->sub_id,
                "clientEmail"=> $process->sub_client_email,
                "clientCell"=> $process->sub_client_cell,
                "interviewDate"=> $process->sub_interview_date,
                "interviewPanel"=> $process->sub_interview_panel,
                "time"=> $process->sub_time
            );

            $result['contact'][] = array(
                "id"=> $process->cont_id,
                "firstName"=> $process->cont_first_name,
                "lastName"=> $process->cont_last_name,
                "email"=> $process->cont_email,
                "mobile"=> $process->cont_mobile
            );
        }
    
    	$response = [
            'success' => true,
            'data'    => $result,
            'message' => 'All List',
        ];
        return response()->json($response, 200);
    }

    public function getToken()
    {
        $request = request(['email', 'password']);
        $user = User::where('email', $request['email'])->first();
        
        if (!$user || !\Hash::check($request['password'], $user->password)) {
            $response = [
                'success' => false,
                'message' => 'Incorrect Email or Password',
            ];
            return response()->json($response, 402);
        }
               
        $response = [
            'success' => true,
            'token'    => $user->createToken('myApp')->plainTextToken
        ];

        return response()->json($response, 200);
    }
}