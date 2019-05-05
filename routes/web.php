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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/voice', 'HomeController@voice')->name('voice');
Route::get('/sms', 'HomeController@sms')->name('sms');
Route::get('/askforhelp', 'HomeController@askforhelp')->name('askforhelp');


Route::get('/sendsms', 'HomeController@sendsms')->name('sendsms');
Route::get('/makeacall', 'HomeController@makeacall')->name('makeacall');
Route::get('/friend/request/{id}', 'HomeController@sendFriendRequest')->name('sendFriendRequest');
