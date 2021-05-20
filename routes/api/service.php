<?php
Route::post('commands/{command}/change-command', [\App\Http\Controllers\Service\CommandController::class, 'changeCommand'])->middleware(['auth:wallet']);;
Route::post('commands/{ref}/request', [\App\Http\Controllers\Service\CommandController::class, 'requestCommand'])->middleware(['auth:wallet']);
Route::apiResource('commands', CommandController::class)->middleware(['auth:wallet']);

Route::apiResource('platform-levels', PlatformLevelController::class);
Route::apiResource('platforms', PlatformController::class)->middleware(['auth:wallet']);
