<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ajaxdataController extends Controller
{
    public function ajaxdata(){
        $email = session('email');
        $password = session('password');

        $data = DB::table('employees')->where('email', $email)->where('password', $password)->first();
        $data = json_encode($data);
      return $data;
    }
}
