<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::middleware('auth:api')->group(function (){

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('change-password','UserController@changePassword');
});

Route::post('register','Auth\RegisterController@register');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');
Route::post('send-password-reset-mail','UserController@sendPasswordResetMail');
Route::post('reset-password','UserController@resetPassword');
