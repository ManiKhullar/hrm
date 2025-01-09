<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\ClientMasterImport;
use Illuminate\Support\Facades\Auth;

class ClientMasterImportController extends Controller
{
    public function clientmaster_list(){
        $clientMaster = DB::table('client_master_list')->paginate(10);
        return view('clientmasterimport.listclientmaster',[
            'clientMaster'=> $clientMaster ])->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function clientmaster_add(){
        return view('clientmasterimport.addclientmaster');
    }

    public function clientmaster_save(Request $request)
    {
        $request->validate([
            'technology' => 'required',
            'company' => 'required',
            'name' => 'required',
            'contact_number' => 'required',
        ]);
        if((Auth::user()->role) == 6 || (Auth::user()->role) == 5){
            ClientMasterImport::create([
                'technology' => $request->technology,
                'interview_date' => $request->interview_date,
                'company' =>  $request->company,
                'name' =>  $request->name,
                'contact_person' =>  $request->contact_person,
                'client_email' =>  $request->client_email,
                'contact_number' =>  $request->contact_number,
                'source' =>  $request->source,
                'rate' =>  $request->rate,
                'pre_call_notes' =>  $request->pre_call_notes,
                'meeting_link' =>  $request->meeting_link,
                'post_call_notes' =>  $request->post_call_notes,
                'status' =>  $request->status,
                'interview_taken_by' =>  $request->interview_taken_by,
                'end_client' =>  $request->end_client,
                'interview_type' =>  $request->interview_type
            ]);
            return redirect()->route('clientmasterlist')->with('success','Client Master Created Successfully.');
        }else{
            return redirect()->route('clientmasterlist')->with('success','You are not authorized.');
        }
    }

    public function clientmaster_edit($id){
        $clientmaster = ClientMasterImport::where('id', $id)->firstOrFail();
        return view('clientmasterimport.editclientmaster',[
            'clientmaster'=> $clientmaster ])->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function clientmaster_update(Request $request,$id){
        $clientmaster = ClientMasterImport::where('id', $id)->firstOrFail();
        $clientmaster->update([
            'technology' => $request->technology,
            'interview_date' => $request->interview_date,
            'company' =>  $request->company,
            'name' =>  $request->name,
            'contact_person' =>  $request->contact_person,
            'client_email' =>  $request->client_email,
            'contact_number' =>  $request->contact_number,
            'source' =>  $request->source,
            'rate' =>  $request->rate,
            'pre_call_notes' =>  $request->pre_call_notes,
            'meeting_link' =>  $request->meeting_link,
            'post_call_notes' =>  $request->post_call_notes,
            'status' =>  $request->status,
            'interview_taken_by' =>  $request->interview_taken_by,
            'end_client' =>  $request->end_client,
            'interview_type' =>  $request->interview_type
        ]);
        return view('clientmasterimport.editclientmaster',[
            'clientmaster'=> $clientmaster ])->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function clientmaster_filter(Request $request)
    {
        $clientMaster = DB::table("client_master_list")
            ->where('technology', $request->technology)
            ->orWhere('interview_taken_by', $request->interview_taken_by)
            ->orWhere('company', $request->company)
            ->orWhere('name', $request->name)
            ->paginate(10,array('id','company','technology','name','contact_person','client_email','contact_number','interview_date','source','rate','pre_call_notes','meeting_link','post_call_notes','status','interview_taken_by','end_client','interview_type'));

        if(!empty($request->technology)){
            $clientMaster->appends(['technology'=>$request->technology]);
        }
        if(!empty($request->interview_taken_by)){
            $clientMaster->appends(['interview_taken_by'=>$request->interview_taken_by]);
        }
        if(!empty($request->company)){
            $clientMaster->appends(['company'=>$request->company]);
        }
        if(!empty($request->name)){
            $clientMaster->appends(['name'=>$request->name]);
        }

        return view('clientmasterimport.listclientmaster',[
            'clientMaster'=> $clientMaster
        ]);
    }
    
    public function clientmaster(){
        return view('clientmasterimport.index');
    }

    public function clientmaster_import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);
        if((Auth::user()->role) == 6 || (Auth::user()->role) == 5){
            $path = public_path('clientMasterImport/');
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

                if(trim($importData[$counter-1]['id']) === ''){
                    return redirect()->route('clientmaster')->with('error','Please fill id column data.');
                }else{
                    $clientMaster = DB::select("SELECT * FROM client_master_list WHERE id = '".$importData[$counter-1]['id']."'");
                    if(isset($clientMaster[0]->id) && ($clientMaster[0]->id)!=''){
                        $clientMasterList = DB::table('client_master_list')
                        ->where('id', $clientMaster[0]->id)
                        ->update(array(
                            'technology' => $importData[$counter-1]['technology'],
                            'interview_date' => $importData[$counter-1]['interview_date'],
                            'company' => $importData[$counter-1]['company'],
                            'name' => $importData[$counter-1]['name'],
                            'contact_person' => $importData[$counter-1]['contact_person'],
                            'client_email' => $importData[$counter-1]['client_email'],
                            'contact_number' => $importData[$counter-1]['contact_number'],
                            'source' => $importData[$counter-1]['source'],
                            'rate' => $importData[$counter-1]['rate'],
                            'pre_call_notes' => $importData[$counter-1]['pre_call_notes'],
                            'meeting_link' => $importData[$counter-1]['meeting_link'],
                            'post_call_notes' => $importData[$counter-1]['post_call_notes'],
                            'status' => $importData[$counter-1]['status'],
                            'interview_taken_by' => $importData[$counter-1]['interview_taken_by'],
                            'end_client' =>  $importData[$counter-1]['end_client'],
                            'interview_type' =>  $importData[$counter-1]['interview_type']
                        ));
                    }else{
                        $clientMasterList = ClientMasterImport::create([
                            'id' => $importData[$counter-1]['id'],
                            'technology' => $importData[$counter-1]['technology'],
                            'interview_date' => $importData[$counter-1]['interview_date'],
                            'company' => $importData[$counter-1]['company'],
                            'name' => $importData[$counter-1]['name'],
                            'contact_person' => $importData[$counter-1]['contact_person'],
                            'client_email' => $importData[$counter-1]['client_email'],
                            'contact_number' => $importData[$counter-1]['contact_number'],
                            'source' => $importData[$counter-1]['source'],
                            'rate' => $importData[$counter-1]['rate'],
                            'pre_call_notes' => $importData[$counter-1]['pre_call_notes'],
                            'meeting_link' => $importData[$counter-1]['meeting_link'],
                            'post_call_notes' => $importData[$counter-1]['post_call_notes'],
                            'status' => $importData[$counter-1]['status'],
                            'interview_taken_by' => $importData[$counter-1]['interview_taken_by'],
                            'end_client' =>  $importData[$counter-1]['end_client'],
                            'interview_type' =>  $importData[$counter-1]['interview_type']
                        ]);
                    }
                }
                $counter++;
            }
            fclose($file);
            return redirect()->route('clientmaster')->with('success','Client Master Data Imported Successfully.');
        }else{
            return redirect()->route('clientmaster')->with('success','You are not authorized.');
        }
    }
}
