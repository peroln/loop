<?php

use Illuminate\Support\Facades\Route;


Route::resource('questions', 'QuestionController');
Route::resource('answers', 'AnswerController');
