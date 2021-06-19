<?php

use Illuminate\Support\Facades\Route;
//use \App\Http\Controllers\Admin\LanguageController;

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

Route::post('change-password', [\App\Http\Controllers\Admin\Auth\ChangePasswordController::class, 'store']);
Route::resource('language', LanguageController::class);
Route::post('login', [\App\Http\Controllers\Admin\Auth\AuthController::class, 'login']);
Route::post('registration', [\App\Http\Controllers\Admin\Auth\RegistrationController::class, 'registration'])->middleware(['auth:wallet']);
Route::match(['get', 'post'],'switcher-2fa', [\App\Http\Controllers\Admin\Auth\AuthController::class, 'switcher2FA']);
Route::get('get-qr-code', [\App\Http\Controllers\Admin\Auth\AuthController::class, 'getQRCode']);
Route::get('test', [\App\Http\Controllers\Admin\Auth\AuthController::class, 'test']);

