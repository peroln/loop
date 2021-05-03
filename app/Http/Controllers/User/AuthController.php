<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;

use App\Http\Requests\User\RegistrationRequest;
use App\Models\Helpers\CryptoServiceInterface;
use App\Models\User;
use App\Models\Wallet;
use App\Repositories\Base\RepositoryInterface;
use App\Repositories\UserRepository;
use App\Traits\FormatsErrorResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;


/**
 * Class AuthController
 *
 * @package App\Http\Controllers\Shipper\Auth
 */
class AuthController extends Controller
{
    use FormatsErrorResponse;


    /**
     * @var UserRepository
     */
    private RepositoryInterface $user;
    /**
     * @var CryptoServiceInterface
     */
    private CryptoServiceInterface $cryptoService;

    /**
     * AuthController constructor.
     * @param RepositoryInterface $user
     * @param CryptoServiceInterface $cryptoService
     */
    public function __construct(RepositoryInterface $user, CryptoServiceInterface $cryptoService)
    {
        $this->middleware('auth:wallet', ['except' => ['login', 'registration']]);
        $this->cryptoService = $cryptoService;
        $this->user = $user;
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $this->authenticate($request->validated());
        if (!auth()->check()) {
            response()->json(['error' => 'Authentication is failure'], 501);
        }
        try {
            $token = auth()->fromUser(auth()->user());
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 502);
        }

        $user = $this->user->getUserByWallet(auth()->user()->address);
        $expires_in = $this->getExpiresTime();

        return response()->json(compact('token', 'expires_in', 'user'), 200);
    }

    /**
     * @return int
     */
    private function getExpiresTime(): int
    {
        return auth()->factory()->getTTL() * 60;
    }

    /**
     * @param array $valid_request
     */
    private function authenticate(array $valid_request): void
    {
        $address = $valid_request['address'];
        $wallet = Wallet::where('address', $address)->firstOrFail();
        Auth::login($wallet);
    }

    public function registration(RegistrationRequest $request)
    {
        $data_event = response()->json($this->cryptoService->confirmRegistration($request->input('hex')));
        // TODO create user, wallet, transaction
        $user_data_params = [];
        $user = $this->user->create($user_data_params);
        $token = 'token'; //$token = \JWTAuth::fromUser($wallet);

        return response()->json(compact('user', 'token'), 201);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthenticatedUser(): JsonResponse
    {
        $user = $this->user->getUserByWallet(Auth::user()->address);
        return response()->json(compact('user'));
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(): JsonResponse
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(): JsonResponse
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->getExpiresTime()
        ]);
    }
}
