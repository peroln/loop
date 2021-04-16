<?php


use App\Http\Controllers\Admin\Auth\AuthController;
use App\Http\Controllers\Admin\Auth\Authy2FAController;
use App\Http\Controllers\Admin\Auth\ForgotPasswordController;
use App\Http\Controllers\Admin\Management\AdminsController;
use App\Http\Controllers\Admin\Management\UsersController;
use App\Http\Controllers\Admin\SettingsController;
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



