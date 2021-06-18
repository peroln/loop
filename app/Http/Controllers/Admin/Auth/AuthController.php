<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserLogin2FARequest;
use App\Http\Requests\Admin\UserRegistrationRequest;
use App\Http\Requests\Admin\UserSwitcher2FARequest;
use App\Http\Resources\User\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Services\AdminService;
use App\Traits\FormatsErrorResponse;
use BaconQrCode\Encoder\QrCode;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use PragmaRX\Google2FA\Google2FA;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

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
     * @param  \App\Services\AdminService  $service
     */
    public function __construct()
    {
        $this->middleware(['auth:admins'])->except('login', 'confirmEmail', 'registration');
    }

    public function login(Request $request)
    {

        if (Auth::guard('users')->attempt([
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
        ])) {
            $user = Auth::guard('users')->user();
            if ($user->google2fa) {
                return  response()->json(['turn to login2FA']);
            }
            return $this->getRegisterUserToken($user);
        }
        if (!auth()->check()) {
            response()->json(['error' => 'Authentication is failure'], 401);
        }

    }

    /**
     * @param  User  $user
     *
     * @return JsonResponse
     */
    private function getRegisterUserToken(User $user): JsonResponse
    {
        try {
            $token = Auth::guard('admins')->fromUser($user);

        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 502);
        }
        $expires_in = $this->getExpiresTime();

        return response()->json(compact('token', 'expires_in'), 200);
    }

    /**
     * @param  Request  $request
     *
     * @return mixed
     */
    public function registration(UserRegistrationRequest $request)
    {
        $user            = User::whereHas('wallet', fn($q) => $q->where('address', $request->input('contract_address')))->firstOrFail();
        $user->user_name = $request->input('user_name');
        $user->email     = $request->input('email');
        $user->password  = Hash::make($request->input('password'));
        $user->role_id   = $user->id === 1 ? Role::whereName('admin')->firstOrFail()->id : Role::whereName('user')->firstOrFail()->id;
        $user->save();
        return new UserResource($user);

    }

    /**
     * @return JsonResponse
     * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     * @throws \PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException
     */
    public function getQRCode(): JsonResponse
    {
        $user = Auth::guard('admins')->user();
        $google2fa              = new Google2FA();
        $user->google2fa_secret = $google2fa->generateSecretKey();
        $user->save();

        $src = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->google2fa_secret
        );
        return response()->json(compact('src'));

    }

    /**
     * @param  UserLogin2FARequest  $request
     *
     * @return JsonResponse
     * @throws \PragmaRX\Google2FA\Exceptions\IncompatibleWithGoogleAuthenticatorException
     * @throws \PragmaRX\Google2FA\Exceptions\InvalidCharactersException
     * @throws \PragmaRX\Google2FA\Exceptions\SecretKeyTooShortException
     */
    public function login2FA(UserLogin2FARequest $request)
    {
        $user      = Auth::guard('admins')->user();

        $google2fa = new Google2FA();
        $valid     = $google2fa->verifyKey($user->google2fa_secret, $request->input('secret'));
        if ($valid) {
           return $this->getRegisterUserToken($user);
        }
        return response()->json(['error' => 'Secret is failure'], 401);
    }

    /**
     * @return int
     */
    private function getExpiresTime(): int
    {
        return Auth::guard('admins')->factory()->getTTL() * 60 * 1000;
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
        $user = Auth::guard('admins')->user();
        $user->google2fa = $request->input('google2fa');
        $user->save();
        return response()->json(['The google2fa is '. $user->google2fa]);
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
