<?php

declare(strict_types=1);

namespace App\Http\Controllers\User\Auth;

use App\Exceptions\ErrorMessages;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Auth\GoogleAuthRequest;
use App\Http\Requests\User\Auth\LoginRequest;
use App\Http\Requests\User\Auth\RegisterRequest;
use App\Http\Resources\User\Auth\UserDataResource;
use App\Services\User\Auth\GoogleAuthService;
use App\Services\UserService;
use App\Traits\FormatsErrorResponse;
use Authy\AuthyApi;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * Class AuthController
 *
 * @package App\Http\Controllers\Shipper\Auth
 */
class AuthController extends Controller
{
    use FormatsErrorResponse;

    private UserService $service;

}
