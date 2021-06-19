<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserLogin2FARequest;
use App\Http\Requests\Admin\UserSwitcher2FARequest;
use App\Models\User;
use App\Services\AdminService;
use App\Services\AuthAdminService;
use App\Traits\FormatsErrorResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class AuthController extends Controller
{
    use FormatsErrorResponse;


    /**
     * @var \App\Services\AdminService
     */
    private AdminService     $service;
    private AuthAdminService $authAdminService;

    /**
     * AuthController constructor.
     *
     * @param  AuthAdminService  $authAdminService
     */
    public function __construct(AuthAdminService $authAdminService)
    {
        $this->middleware(['auth:admins'])->except('login', 'confirmEmail', 'registration', 'test');
        $this->authAdminService = $authAdminService;
    }

    /**
     * @param  UserLogin2FARequest  $request
     *
     * @return JsonResponse
     */
    public function login(UserLogin2FARequest $request): JsonResponse
    {
        return $this->authAdminService->login($request);
    }


    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        Auth::guard('admins')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->guard('admins')->refresh());
    }


    public function switcher2FA(UserSwitcher2FARequest $request): JsonResponse
    {
        return $this->authAdminService->switcher2FA($request);
    }

    /**
     * @param  string  $token
     *
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => $this->getExpiresTime(),
        ]);
    }

}
