<?php

namespace App\Http\Controllers;
use App\user;
use Image;
use DB;
use Session;
use Auth;
use App\Http\Requests\userRequest;
use Illuminate\Http\Request;

class userController extends Controller
{
    public function insert(userRequest $request){

        $user  = new user() ;
        $user->_token = $request->_token;
        $user->first_name = $request->first_name;       
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->state = $request->state;
        $user->zip = $request->zip;
        $data     = DB::select('select * from users where email = :email and password = :password', ['email' => $user->email, 'password' => $user->password]);
        if($data){
            return redirect()->back()->withSuccess('User Already Exists Please Go To Login Page');
        }else{   
            if($request->hasFile('image')){
                
                $images = $request->file('image');
                $name=$images->getClientOriginalName();
                $images->move(public_path().'/images/', $name);  
                $data[] = $name; 
                $user->image = $name; 
               
            };
            $user->save();
        return redirect()->back()->withSuccess('submitted successfully');
        }
    }   
    public function select(Request $request){
            $email = $request->input('email');
            $password = $request->input('password');
        if (Auth::attempt(['email' => $email, 'password' => $password]))
        {
            return redirect()->intended('profile');
        }
   
    }
    public function getprofileredirect(){
        return view('/home');
    } 

    
}



// $email = $request->input('email');
// $password = $request->input('password');
// if($email == null){
//    return view('/home');
// }else{
//    $users = DB::table('users')->where('email', $email)
//                            ->where('password', $password) 
//                            ->get()->first();  
//        // print_r($users);die;
//        if(!$users){
//            return view('/home');
//        }else{
//            Session::put('users', $users);
//            return view('profile',['users' => $users]);
//        }
// }