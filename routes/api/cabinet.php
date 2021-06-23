<?php

use Illuminate\Support\Facades\Route;

Route::apiResource('questions', 'QuestionController');
Route::apiResource('answers', 'AnswerController');
Route::apiResource('black-lists', 'BlackListController')->middleware('auth:admins');
