<?php
Route::get('/contract_address', 'PublicApiController@address');
Route::get('/all', 'UserController@getAllUsers');
Route::get('/{id}', 'UserController@getUserById');