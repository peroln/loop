<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\GetUserByIdRequest;
use App\Http\Requests\User\GetUserByWalletRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Models\Language;
use App\Models\User;
use App\MultiplePaginate;
use App\Http\Requests\User\GetAllUserRequest;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;


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
     *
     */
    public function update(UserUpdateRequest $request, User $user): UserResource|JsonResponse
    {
        $params = $request->validated();
        if ($request->has('language')) {
            $language_shortcode = Arr::pull($params, 'language');
            $lang_model = Language::where('shortcode', $language_shortcode)->firstOrFail();
            $user->language_id = $lang_model->id;
        }
        if (count($params)) {
            $user->fill($params);
        }
        if ($user->save()) {
            return new UserResource($user);
        };
        return response()->json('The model is`t update', 400);

    }

    /**
     * @param WalletRepository $wallet_repository
     * @param string $wallet
     * @return JsonResponse
     */
    public function checkWallet(WalletRepository $wallet_repository, string $wallet): JsonResponse
    {
        return response()->json($wallet_repository->exist('address', $wallet));

    }

    public function getReferralReit()
    {

    }

    /**
     * @param int $contract_user_id
     * @return UserResource
     * @throws \ReflectionException
     */
    public function getUserByContractId(int $contract_user_id): UserResource
    {
        return new UserResource($this->userRepository->findByOrFail('contract_user_id', $contract_user_id));

    }
}
