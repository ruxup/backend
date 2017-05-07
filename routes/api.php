<?php

use Illuminate\Http\Request;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('jwt:auth');


Route::post('register', array('as' => 'user.register', 'uses' => 'Auth\RegisterController@Register'));
Route::get('logout', array('as' => 'user.logout', 'uses' => 'Auth\LoginController@logout'));
Route::post('login', array('as' => 'user.login', 'uses' => 'Auth\LoginController@login'));

Route::group(['prefix' => 'user'], function (){
    Route::get('{id}/events', array('as' => 'user.events', 'uses' => 'UserController@getEvents'));
    Route::post('{id}/rate', array('as' => 'user.rate', 'uses' => 'UserController@rate'));
    Route::post('{id}/chat', array('as' => 'user.chat', 'uses' => 'UserController@chat'));
    Route::get('{id}/owner', array('as' => 'admin.events', 'uses' => 'UserController@getEventsWhereOwner'));
    Route::get('/', array('as' => 'user.profile', 'uses' => 'Auth\LoginController@getAuthenticatedUser'));
    Route::put('{id}', array('as' => 'user.update', 'uses' => 'Auth\EditController@UpdateProfile'));
    Route::delete('{id}', array('as' => 'user.remove', 'uses' => 'UserController@removeProfile'));
    Route::get('{id}', array('as' => 'user.restore', 'uses' => 'UserController@restoreProfile'));
});

Route::group(['prefix' => 'password'], function() {
    Route::post('reset',  array('as' => 'password.reset', 'uses' => 'Auth\ResetPasswordController@reset'));
    Route::post('email',  array('as' => 'password.email', 'uses' => 'Auth\ForgotPasswordController@getResetToken'));
});

Route::group(['prefix' => 'event'], function (){
    Route::get('find/{type}/{start_time?}/{end_time?}/{location?}/{category?}',array('as' => 'event.find', 'uses' => 'UserController@findEvent'));
    Route::post('/', array('as' => 'event', 'uses' => 'EventController@create'));
    Route::get('{id}/members', array('as' => 'event.members', 'uses' => 'EventController@getUsers'));
    Route::get('{id}/restore', array('as' => 'event.restore', 'uses' => 'EventController@restoreEvent'));
    Route::put('{id}/update', array('as' => 'event.update', 'uses' => 'EventController@updateEvent'));
    Route::post('{id}/owner/', array('as' => 'admin.change', 'uses' => 'UserController@changeOwner'));
    Route::delete('{id}/owner/{user_id}', array('as' => 'event.kick', 'uses' => 'EventController@kick'));
    Route::get('{id}/{userid}', array('as' => 'user.join', 'uses' => 'UserController@joinEvent'));
    Route::delete('{id}/{userid}', array('as' => 'event.leave', 'uses' => 'EventController@leaveEvent'));
    Route::get('{id}', array('as' => 'event.dashboard', 'uses' => 'EventController@getEvent'));
    Route::delete('{id}', array('as' => 'event.remove', 'uses' => 'EventController@removeEvent'));
});

Route::get('events/{columnNr}/{orderType}', array('as' => 'event.all', 'uses' => 'EventController@getAllEvents')); // limit

Route::group(['prefix' => 'messages'], function (){
    Route::post('/',  array('as' => 'message.send', 'uses' => 'MessageController@comment'));
    Route::delete('{id}/{owner_id}',  array('as' => 'message.delete', 'uses' => 'MessageController@delete'));
    Route::post('{id}/{owner_id}',  array('as' => 'message.edit', 'uses' => 'MessageController@edit'));
});

Route::group(['prefix' => 'interests'], function () {
    Route::post('/',  array('as' => 'interests.add', 'uses' => 'InterestsController@add'));
    Route::delete('{user_id}/{interest_id}',  array('as' => 'interests.remove', 'uses' => 'InterestsController@delete'));
});

//handle update - see image; //handle images ->load images
//Change all responses to json;

//shorter address for api calls.
//swagger
//handle report user
//Setup ruxup email.

//handle invite another user to join event. - for Version 2.0
//handle pending/accept when user wants to join an event. - for Version 2.0
