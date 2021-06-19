<?php

Route::post('change-password', [\App\Http\Controllers\Admin\Auth\ChangePasswordController::class, 'store']);
Route::apiResource('languages', 'LanguageController');
Route::apiResource('videos', 'VideoController')->middleware(['auth:admins']);
Route::post('login', [\App\Http\Controllers\Admin\Auth\AuthController::class, 'login']);
Route::post('registration', [\App\Http\Controllers\Admin\Auth\RegistrationController::class, 'registration'])->middleware(['auth:wallet']);
Route::match(['get', 'post'], 'switcher-2fa', [\App\Http\Controllers\Admin\Auth\AuthController::class, 'switcher2FA']);
Route::get('get-qr-code', [\App\Http\Controllers\Admin\Auth\AuthController::class, 'getQRCode']);
Route::get('test', [\App\Http\Controllers\Admin\Auth\AuthController::class, 'test']);

