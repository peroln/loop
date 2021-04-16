<?php

Route::get('/', 'PingController@show');
Route::post('/', 'PingController@store');
Route::put('/', 'PingController@update');
Route::delete('/', 'PingController@delete');
