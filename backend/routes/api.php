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

header('Access-Control-Allow-Origin:'. $_SERVER['HTTP_ORIGIN']);
header('Access-Control-Allow-Headers:' . 'accept, content-type,x-xsrf-token, x-csrf-token');
header('Access-Control-Allow-Methods:' . 'GET, POST, PUT, DELETE, OPTIONS');

Route::group(['middleware' => 'cors'], function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware('jwt:auth');

    //User
    Route::post('login', 'Auth\LoginController@login');

    Route::get('profile', 'Auth\LoginController@getAuthenticatedUser');
    Route::post('register', 'Auth\RegisterController@postRegister');
    Route::get('logout', 'Auth\LoginController@logout');

    //Edit user profile
    Route::put('profile/{id}', 'Auth\EditController@putUpdateProfile');

    //Create event
    Route::post('create_event', 'EventController@create');
});





