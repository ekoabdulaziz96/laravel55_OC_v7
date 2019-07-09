<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Events\Auth\UserActivationEmail;
class ActivationResendController extends Controller
{
    public function showResendForm(){
    	return view('auth.activate.resend');
    }    
    public function resend(Request $request){
    	$this->validateResend($request);
    	$user= User::where('email',$request->email)->first();
    	event (new UserActivationEmail($user));
    	return redirect()->route('login')->withSuccess('email activation has been resend');
    	// return view('auth.activate.resend');
    }

    protected function validateResend(Request $request){
    	$this->validate($request,[
    		'email'=>'required|email|exists:users,email'
    	],[
    		'email.exists'=>'this email is not exists. please check your email'
    	]);
    }
}
