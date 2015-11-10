<?php

Route::get('/', function () {
    return view('home');
});

Route::post('incoming', 'TwilioController@receive');
