<?php

namespace App\Http\Controllers;
use Redirect;
use Auth;
use Validator;
use Input;
use App\Employee;
use Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class addemployee extends Controller
{


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function insert(Request $request){
        $validator = Validator::make($request->all(), [
            'email'    => 'required|unique:users|email|max:100',
            'password' => 'required|min:6|max:100',
        ]);
            // if the validator fails, redirect back to the form
            if ($validator->fails())
              {
              return Redirect::to('addemployee')->withErrors($validator) // send back all errors to the login form
              ->withInput(Input::except('password')); // send back the input (not the password) so that we can repopulate the form
              }
              else
              {
                  $email = $request->email;
                  $user = DB::table('employees')->where('email', $email)->first();
              if ($user)
                {
                    return Redirect::to('addemployee')->with('message', 'email already exits');

                }
                else
                {
                    $Employee = new employee;
                    $Employee->name = $request->name;
                    $Employee->email = $request->email;
                    $Employee->password = MD5($request->password);
                    $Employee->designation = $request->designation;
                    $Employee->joiningdate = $request->joiningdate;
                    $Employee->dateofbirth = $request->dateofbirth;
                    $imageName = $Employee->id.'_image'.time().'.'.request()->image->getClientOriginalExtension();   
                    $request->image->storeAs('images',$imageName);
                    $Employee->image = $imageName;
                    $Employee->save();
                    return back()->with('employees', $Employee);
                }
  
    }
}
}