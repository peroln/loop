<?php

Route::get('articles/{article}', [\App\Http\Controllers\Common\ArticleController::class, 'show']);
Route::get('videos/{video}', [\App\Http\Controllers\Admin\VideoController::class, 'show']);
