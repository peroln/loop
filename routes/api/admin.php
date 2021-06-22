<?php

Route::post('change-password', [\App\Http\Controllers\Admin\Auth\ChangePasswordController::class, 'store']);
Route::apiResource('languages', 'LanguageController');

Route::post('login', [\App\Http\Controllers\Admin\Auth\AuthController::class, 'login']);
Route::post('registration', [\App\Http\Controllers\Admin\Auth\RegistrationController::class, 'registration'])->middleware(['auth:wallet']);
Route::match(['get', 'post'], 'switcher-2fa', [\App\Http\Controllers\Admin\Auth\AuthController::class, 'switcher2FA']);
Route::get('get-qr-code', [\App\Http\Controllers\Admin\Auth\AuthController::class, 'getQRCode']);
Route::get('test', [\App\Http\Controllers\Admin\Auth\AuthController::class, 'test']);

Route::middleware(['auth:admins'])->group(function () {
    Route::apiResource('videos', 'VideoController');
    Route::get('users', [\App\Http\Controllers\User\UserController::class, 'indexAdmin']);
    Route::get('common-info', [\App\Http\Controllers\User\UserController::class, 'getCommonInfo']);
    Route::get('last-platforms', [\App\Http\Controllers\Service\PlatformController::class, 'getLastCompletePlatforms']);
});


