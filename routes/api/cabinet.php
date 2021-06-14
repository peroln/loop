<?php

use Illuminate\Support\Facades\Route;



Route::middleware(['auth:wallet'])->group(function () {
    Route::resource('questions', 'QuestionController');
    Route::resource('answers', 'AnswerController');
    Route::resource('black-lists', 'BlackListController');
});
