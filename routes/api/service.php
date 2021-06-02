<?php
Route::post('commands/{command}/change-command', [\App\Http\Controllers\Service\CommandController::class, 'changeCommand'])->middleware(['auth:wallet']);;
Route::post('commands/{ref}/request', [\App\Http\Controllers\Service\CommandController::class, 'requestCommand']);
Route::apiResource('commands', CommandController::class)->middleware(['auth:wallet']);

Route::get('platforms/wallets/{wallet}', [\App\Http\Controllers\Service\PlatformController::class, 'platformUsersInfo']);
Route::get('cabinet/info', [\App\Http\Controllers\Service\CabinetController::class, 'mainInformation']);
Route::get('cabinet/league-rating', [\App\Http\Controllers\Service\CabinetController::class, 'leagueRating']);
Route::get('cabinet/league-desk', [\App\Http\Controllers\Service\CabinetController::class, 'LeagueDesk']);

Route::apiResource('platform-levels', PlatformLevelController::class);
Route::apiResource('platforms', PlatformController::class)->middleware(['auth:wallet']);
Route::apiResource('platform-reactivation', ReactivationController::class)->middleware(['auth:wallet']);
