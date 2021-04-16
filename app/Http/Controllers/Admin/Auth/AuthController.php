<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Http\Resources\Admin\Auth\AdminDataResource;
use App\Http\Resources\User\Auth\UserDataResource;
use App\Services\AdminService;
use App\Services\Base\BaseAppGuards;
use App\Traits\FormatsErrorResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use FormatsErrorResponse;

    /**
     * @var \App\Services\AdminService
     */
    private AdminService $service;

    /**
     * AuthController constructor.
     *
     * @param \App\Services\AdminService $service
     */
    public function __construct(AdminService $service)
    {
        $this->service = $service;
        $this->middleware(['auth:admin'])->except('login', 'confirmEmail');
    }

}
