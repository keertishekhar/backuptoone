<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Redirect;
use Illuminate\Support\Facades\Auth;
use Validator;
use Input;
use Session;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class employeeController extends Controller
{

public function __construct(){


}


    public function index(){
        if (Auth::check()) {
           return redirect()->route('home');
        }else{
        if(Session::has('user'))
        {
            $email = session('email');
            $password = session('password');
            $user = DB::table('employees')->where('email', $email)->where('password', $password)->first();
            
            return view('/employeeprofile',compact('user'));
        }
        return view('/employeelogin');
    }
}

    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            'email'    => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails())
        {
        return Redirect::to('addemployee')->withErrors($validator) // send back all errors to the login form
        ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
        }
        else
        {
            $email = $request->email;
            $password = MD5($request->password);
           
            $user = DB::table('employees')->where('email', $email)->where('password', $password)->first();
           if($user){
            Session::put('user', $user);
            Session::put('email', $email);
            Session::put('password',$password);
            Session::put('id',$user->id);
            Session::put('name',$user->name);
                return view('/employeeprofile',compact('user'));
           }else{
               return redirect()->back()->with('message', 'Wrong Credentials ');
           }
        }
    }
    public function logout(){

            Session::flush('user');
            Session::flush('email');
            Session::flush('password');
            return view('/employeelogin');
    }
}
