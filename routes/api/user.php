<?php
use \App\Http\Controllers\User\UserController;
Route::get('/contract_address', 'PublicApiController@address');
Route::get('/all', 'UserController@getAllUsers');
Route::get('/wallet/{address}', [UserController::class, 'getUserByWallet']);
Route::get('/{id}', 'UserController@getUserById');
