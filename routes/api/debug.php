<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/', fn() => response()->noContent(\Illuminate\Http\Response::HTTP_NOT_FOUND));
// Debug routes
Route::group(
    ['middleware' => 'isDebug', 'namespace' => 'Debug'],
    static function (): void {
        Route::get('/ws_client', [\App\Http\Controllers\DebugController::class, 'wsClient']);
        Route::get('/libs/{file}', [\App\Http\Controllers\DebugController::class, 'getFile']);
    }
);
