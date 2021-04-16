<?php

use App\Http\Controllers\User\Auth\AuthController;
use App\Http\Controllers\User\Auth\Authy2FAController;
use App\Http\Controllers\User\Auth\EmailConfirmationController;
use App\Http\Controllers\User\Auth\ForgotPasswordController;
use App\Http\Controllers\User\KycController;
use App\Http\Controllers\User\OrdersController;
use App\Http\Controllers\User\SettingsController;
use App\Http\Controllers\User\TransactionsController;
use App\Http\Controllers\User\UserController;
use App\Services\Base\BaseAppGuards;
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

Route::group(
    ['namespace' => 'Auth'],
    static function (): void {

    }
);

Route::group(
    [
        'middleware' => [
            'jwt.token.refresh',
            'active.user',
            'auth:' . BaseAppGuards::USER,
        ],
    ],
    static function (): void {

    }
);
