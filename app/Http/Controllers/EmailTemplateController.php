<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailTemplates;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EmailTemplateController extends Controller
{
    public function etemplate_add(){
        // $user_id = Auth::user()->id;
        $etemplates = DB::table('email_templates')->paginate(15);
        return view('emailtemplate.template',['etemplates'=> $etemplates])->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function etemplate_save(Request $request)
    {
        // $user_id = Auth::user()->id;
        $request->validate([
            'subject' => 'required',
            'type' => 'required',
            'content' => 'required'
        ]);

        EmailTemplates::create([
            'subject' => $request->subject,
            'type' => $request->type,
            'content' => $request->content,
            'status' => '1'
        ]);
        return redirect()->route('etemplate')->with('success','Email Template Added Successfully.');          
    }

    public function etemplate_edit($id)
    {
        $template = EmailTemplates::where('id', $id)->firstOrFail();
        return view('emailtemplate.templateedit', compact('template'));
    }

    public function etemplate_update(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required',
            'type' => 'required',
            'content' => 'required'
        ]);
        $holiday = EmailTemplates::where('id', $id)->firstOrFail();
        $holiday->update([
            'subject' => $request->subject,
            'type' => $request->type,
            'content' => $request->content,
            'status' => $request->status
        ]);
        return redirect()->route('etemplate')->with('success','Email Template Updated Successfully.');
    }

}
