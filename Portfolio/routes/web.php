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
    return view('home');
});

Route::get('/login', function () {
    return view('login');
});
Route::get('/logout', function () {
    Session::forget('users');
    return view('home');
});
// Route::post('/login', 'userController@select');
Route::get('/register', function () {
    return view('register');
});
Route::post('/register', 'userController@insert');

Route::get('/profile', 'userController@select')->middleware('auth');

