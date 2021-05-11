<?php

use \App\Http\Controllers\User\UserController;
use  \App\Http\Controllers\User\AuthController;


Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('registration', [AuthController::class, 'registration']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('getAuthenticatedUser', [AuthController::class, 'getAuthenticatedUser']);
});

Route::put('{user}/update', [UserController::class, 'update'])->middleware(['auth:wallet', 'can:update,user']);
Route::get('check-registration/{wallet}', [UserController::class, 'checkWallet']);


Route::get('/contract_address', 'PublicApiController@address');
Route::get('/all', 'UserController@getAllUsers');
Route::get('/wallet/{address}', [UserController::class, 'getUserByWallet']);
Route::get('/{id}', [UserController::class, 'getUserById']);
