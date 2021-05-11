<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\GetUserByIdRequest;
use App\Http\Requests\User\GetUserByWalletRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\MultiplePaginate;
use App\Http\Requests\User\GetAllUserRequest;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\JsonResponse;


class UserController extends Controller
{
    use MultiplePaginate;

    public UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(GetAllUserRequest $request)
    {
        return UserResource::collection(User::paginate($request->per_page));
    }

    /**
     * @param GetUserByIdRequest $request
     * @return UserResource
     */
    public function getUserById(GetUserByIdRequest $request): UserResource
    {
        return new UserResource(User::find($request->input('id')));
    }

    /**
     * @param GetUserByWalletRequest $query
     * @return UserResource
     */

    public function getUserByWallet(GetUserByWalletRequest $query): UserResource
    {
        return $this->userRepository->getUserByWallet($query->input('address'));
    }

    /**
     * @param UserUpdateRequest $request
     * @param User $user
     * @return UserResource|JsonResponse
     */
    public function update(UserUpdateRequest $request, User $user): UserResource | JsonResponse
    {
//        Gate::authorize('update', $user);
        $response = Gate::inspect('update', $user);
        if (!$response->allowed()) {
            return response()->json($response->message(), 403);
        }
//        $this->authorize('update', $user);
        $user->fill($request->validated());
        if($user->save()){
            return new UserResource($user);
        };
        return response()->json('The model is`t update', 400);

    }
}
