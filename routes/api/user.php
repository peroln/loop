<?php

use \App\Http\Controllers\User\UserController;
use  \App\Http\Controllers\User\AuthController;


Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'authenticate']);
    Route::post('registration', 'AuthController@registration');
    Route::post('logout', 'AuthController@logout');
    Route::post('refresh', 'AuthController@refresh');
    Route::post('getAuthenticatedUser', [AuthController::class, 'getAuthenticatedUser']);
});

Route::get('/contract_address', 'PublicApiController@address');
Route::get('/all', 'UserController@getAllUsers');
Route::get('/wallet/{address}', [UserController::class, 'getUserByWallet']);
Route::get('/{id}', [UserController::class, 'getUserById']);
