<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:wallet')->group(function () {
    Route::prefix('answers')->group(function () {
        Route::get('/', [\App\Http\Controllers\Cabinet\AnswerController::class, 'index'])->withoutMiddleware('auth:wallet');
        Route::get('/{answer}', [\App\Http\Controllers\Cabinet\AnswerController::class, 'show'])->withoutMiddleware('auth:wallet');
        Route::post('/', [\App\Http\Controllers\Cabinet\AnswerController::class, 'store']);
        Route::put('/{answer}', [\App\Http\Controllers\Cabinet\AnswerController::class, 'update']);
        Route::delete('/{answer}', [\App\Http\Controllers\Cabinet\AnswerController::class, 'destroy']);
    });
    Route::prefix('questions')->group(function () {
        Route::get('/', [\App\Http\Controllers\Cabinet\QuestionController::class, 'index'])->withoutMiddleware('auth:wallet');
        Route::get('/{question}', [\App\Http\Controllers\Cabinet\QuestionController::class, 'show'])->withoutMiddleware('auth:wallet');
        Route::post('/', [\App\Http\Controllers\Cabinet\QuestionController::class, 'store']);
        Route::put('/{question}', [\App\Http\Controllers\Cabinet\QuestionController::class, 'update']);
        Route::delete('/{question}', [\App\Http\Controllers\Cabinet\QuestionController::class, 'destroy']);
    });

});

Route::apiResource('black-lists', 'BlackListController')->middleware('auth:admins');
