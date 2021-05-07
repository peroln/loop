<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegistrationRequest;
use App\Models\Wallet;
use App\Repositories\Base\RepositoryInterface;
use App\Repositories\UserRepository;
use App\Services\CryptoHandlerService;
use App\Traits\FormatsErrorResponse;
use Illuminate\Http\JsonResponse;
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
     * @var CryptoHandlerService
     */

    private CryptoHandlerService $cryptoHandlerService;

    /**
     * AuthController constructor.
     * @param RepositoryInterface $user
     * @param CryptoHandlerService $cryptoHandlerService
     */
    public function __construct(CryptoHandlerService $cryptoHandlerService, RepositoryInterface $user)
    {
        $this->middleware('auth:wallet', ['except' => ['login', 'registration']]);
        $this->user = $user;
        $this->cryptoHandlerService = $cryptoHandlerService;
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
        $expires_in = $this->getExpiresTime();
        return response()->json(compact('token', 'expires_in'), 200);
    }

    /**
     * @return int
     */
    private function getExpiresTime(): int
    {
        return auth()->factory()->getTTL() * 60 * 1000;
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

    /**
     * @throws \Throwable
     */
    public function registration(RegistrationRequest $request)
    {
        $data_event = $this->cryptoHandlerService->cryptoService->confirmRegistration($request->input('hex'));
        if($data_event === false){
            return response()->json('The hex param is invalid.', 400);
        }
        $params = array_merge($request->validated(), $data_event, ['model_service' => $this->cryptoHandlerService->cryptoService->getImplementClass()]);

        $token = $this->cryptoHandlerService->createWithWallet($params);
        $expires_in = $this->getExpiresTime();
        return response()->json(compact('token', 'expires_in'), 201);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAuthenticatedUser(): JsonResponse
    {
        $user = $this->user->getUserByWallet(Auth::user()->address);
        $user->load('wallet.transactions.transactionEvents');
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
