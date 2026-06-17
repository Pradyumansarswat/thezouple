<?php

namespace App\Http\Controllers\masterAdmin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth,Redirect,route,Session,View,Validator,Config,Hash;
use Mail;

class MasterAdmin extends Controller
{
    public function login(Request $request)
    {
        if(Auth::check()){
            if(Auth::user()->user_role=='ADMIN') {
               return redirect(route('dashboard'));
            }
        }
        if($request->isMethod('post')){
            $userdata = array(
                'email' 		=> 	$request->get('email'),
                'password' 		=> 	$request->get('password'),
                'user_role' 	=> 	'ADMIN',
                'is_active' 	=> 	'ACTIVE',
            );
            if (Auth::attempt($userdata)){
                return redirect(route('dashboard'));
            }else{
                $request->session()->flash('alert-danger','Invaild Id and Password !!');
                return Redirect::back() ->withInput();
            }
        }
        else
        {
            return view('masters.login.login');
        }
        
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flash('alert-danger','You are Logout !!');
        return redirect(route('login'));
    }
    
    public function check_mail(Request $request)
    {
       $ip = 123456;
        $name="Proftcode";
        $data['msg']="Hello Proftcode, Your Department is open on Proftscool Application at IP Address - ".$ip;;
        $data['email']="proftcode@gmail.com";
        Mail::raw($data['msg'], function ($message)  use($data) 
        {
            $message->to($data['email'])->subject('Proftcode Authorise System');
        });
        
        echo "Send";
        
    }
}
