<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
 use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
 use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\Models\User;
use Mail;
use Hash;
use Illuminate\Support\Str;
use App\Http\Helper\SendMail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    // use SendsPasswordResetEmails;

    public function forget_password() {
            return view('auth.forget-password'); 
    }

    public function ForgetPasswordStore(Request $request) {
        try{
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);

        $users = DB::table('users')
            ->where('email', $request->email)
            ->update(array(
                'reset_password_token'=>$token,
                'token_status'=>'active'
            ));


            $to = $request->email;
            $from = 'noreply@mybluethink.in';
            $cc = '';
            $reset_token_link =URL::to('reset-password/'.$token);
            $mailflag = true;
            if($mailflag){
                $emailTemplate = DB::select("SELECT * FROM email_templates WHERE type = 'forgot_password_email_template' AND status = '1'");

                $html = str_replace("{{reset_token_link}}",$reset_token_link,$emailTemplate[0]->content);
                SendMail::sendMail($html, $emailTemplate[0]->subject, $to, $from, $cc);
            }   

             } catch (\Exception $e) {

       // echo  $e->getMessage();exit;
        return back()->with('message', $e->getMessage());
    }
            return back()->with('message', 'We have emailed your password reset link!');


    }

    public function ResetPassword($token) {
        return view('auth.forget-password-link', ['token' => $token]);
    }
    
    public function ResetPasswordStore(Request $request) {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|string|min:4|confirmed',
            'password_confirmation' => 'required'
        ]);

        $update = DB::table('users')->where([
            'email' => $request->email, 
            'reset_password_token' => $request->token,
            'token_status' => 'active'
            ])->first();

        if(!$update){
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = User::where('email', $request->email)->update(['password' => Hash::make($request->password)]);

        // Delete password_resets record
        $userdata = User::where('email', $request->email)->update(['token_status' => 'inactive']);

        return redirect('/index')->with('message', 'Your password has been successfully changed!');
    }
}