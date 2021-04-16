<?php

namespace App\Http\Controllers\User;

use App\Enums\BaseAppEnum;
use App\Enums\Tokens\TokenEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Tokens\ListRequest;
use App\Http\Resources\User\Auth\UserDataResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected UserService $service;

    /**
     * UserController constructor.
     *
     * @param UserService $service
     */
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): Response
    {
        return response(UserDataResource::make(Auth::user()), Response::HTTP_OK);
    }
}
