<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Models\User;
use App\Models\Wallet;
use App\Repositories\UserRepository;
use App\Services\UserService;
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


    private UserService $user_service;
    /**
     * @var UserRepository
     */
    private UserRepository $user;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(UserService $user_service, UserRepository $user)
    {
        $this->middleware('auth:wallet', ['except' => ['authenticate', 'registration']]);
        $this->user_service = $user_service;
        $this->user = $user;
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function authenticate(LoginRequest $request): JsonResponse
    {
        $address = $request->input('address');
        $wallet = Wallet::where('address', $address)->first();
        Auth::login($wallet);
        try {
            $token = \JWTAuth::fromUser($wallet);
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        $user = $this->user->getUserByWallet(Auth::user()->address);

        return response()->json(compact('token', 'user'), 200);

    }

    public function register(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'address' => 'required|string|max:255',
            ]
        );

        if ($validator->fails()) {
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create([
//            'name' => $request->get('name'),
//            'email' => $request->get('email'),
//            'password' => Hash::make($request->get('password')),
        ]);

        $token = \JWTAuth::fromUser($user);

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
}
