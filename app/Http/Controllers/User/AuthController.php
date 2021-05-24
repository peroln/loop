<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegistrationRequest;
use App\Http\Resources\User\UserResource;
use App\Repositories\Base\RepositoryInterface;
use App\Repositories\WalletRepository;
use App\Services\CryptoHandlerService;
use App\Traits\FormatsErrorResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
     * AuthController constructor.
     * @param CryptoHandlerService $cryptoHandlerService
     * @param RepositoryInterface $userRepository
     */
    public function __construct(private CryptoHandlerService $cryptoHandlerService, private RepositoryInterface $userRepository)
    {
        $this->middleware('auth:wallet', ['except' => ['login', 'registration']]);

    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        app()->call([self::class, 'authenticate'], ['valid_request' => $request->validated()]);
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
     * @param WalletRepository $wallet_repository
     * @param array $valid_request
     */
    public static function authenticate(WalletRepository $wallet_repository, array $valid_request): void
    {
        $address = $valid_request['address'];
        try{
            $wallet = $wallet_repository->findByOrFail('address', $address);
            Auth::login($wallet);
        }catch(\Exception $e){
            Log::info('Login is fail. The error is: ' . $e->getMessage());
        }
    }


    /**
     * @param RegistrationRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function registration(RegistrationRequest $request)
    {
        /*$data_event = $this->cryptoHandlerService->cryptoService->confirmRegistration($request->input('hex'));
        if($data_event === false){
            return response()->json('The hex param is invalid.', 400);
        }
        $params = array_merge($request->validated(), $data_event, ['model_service' => $this->cryptoHandlerService->cryptoService->getImplementClass()]);

        $token = $this->cryptoHandlerService->createWithWallet($params);
        $expires_in = $this->getExpiresTime();
        return response()->json(compact('token', 'expires_in'), 201);*/
    }

    /**
     * @return UserResource
     */
    public function getAuthenticatedUser(): UserResource
    {
        $user = $this->userRepository->getUserByWallet(Auth::user()->address);
        return new UserResource($user);
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
     * @param string $token
     * @return JsonResponse
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->getExpiresTime()
        ]);
    }
}
