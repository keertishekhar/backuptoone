<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if(Session::has('user')){
        return redirect(route('employeelogin'));
    }else{
    return view('welcome');
    }
});
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/addemployee', function(){
        return view('addemployee');
});
Route::post('/addemployee', 'addemployee@insert');
Route::get('/employeelogin', 'employeeController@index')->name('employeelogin');
Route::post('/employeelogin', 'employeeController@login');
Route::get('/employeelogout', 'employeeController@logout');
Route::get('/attendance', 'attendanceController@index');
Route::get('/attendance_portal', function(){
                return view('attendance_portal');
});
Route::post('/attendance_portal', 'attendanceController@insert');
Route::get('/ajaxdata1',  'ajaxdataController@ajaxdata');
Route::get('/ajaxdata', function(){
    return view('ajaxdata');
});