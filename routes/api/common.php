<?php

Route::apiResource('articles', 'ArticleController')->parameters([
    'articles' => 'article'
]);
