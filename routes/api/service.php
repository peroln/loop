<?php
Route::post('commands/{id}/change-command', [\App\Http\Controllers\Service\CommandController::class, 'changeCommand']);
Route::apiResource('commands', CommandController::class)->middleware(['auth:wallet']);

