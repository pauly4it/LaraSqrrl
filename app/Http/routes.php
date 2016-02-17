<?php

Route::get('/', function () {
    return view('home');
});

Route::group(['prefix' => 'api', 'middleware' => 'cors'], function()
{
    Route::get('users/{phone}', 'UserController@getUser');
});

Route::post('incoming', 'TwilioController@receive');
