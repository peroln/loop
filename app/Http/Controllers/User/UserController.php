<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IndexAdminUsersRequest;
use App\Http\Requests\User\GetUserByContractIdRequest;
use App\Http\Requests\User\GetUserByIdRequest;
use App\Http\Requests\User\GetUserByReferralRequest;
use App\Http\Requests\User\GetUserByWalletRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Http\Resources\User\UserResource;
use App\Models\Language;
use App\Models\User;
use App\MultiplePaginate;
use App\Http\Requests\User\GetAllUserRequest;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Services\CabinetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;


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
     * @param  GetUserByIdRequest  $request
     *
     * @return UserResource
     */
    public function getUserById(GetUserByIdRequest $request): UserResource
    {
        return new UserResource(User::find($request->input('id')));
    }

    /**
     * @param  GetUserByWalletRequest  $query
     *
     * @return UserResource
     */

    public function getUserByWallet(GetUserByWalletRequest $query): UserResource
    {
        return $this->userRepository->getUserByWallet($query->input('address'));
    }

    /**
     * @param  UserUpdateRequest  $request
     * @param  User               $user
     *
     * @return UserResource|JsonResponse
     *
     */
    public function update(UserUpdateRequest $request, User $user): UserResource|JsonResponse
    {
        $params = $request->validated();
        if ($request->has('language')) {
            $language_shortcode = Arr::pull($params, 'language');
            $lang_model         = Language::where('shortcode', $language_shortcode)->firstOrFail();
            $user->language_id  = $lang_model->id;
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
     * @param  WalletRepository  $wallet_repository
     * @param  string            $wallet
     *
     * @return JsonResponse
     */
    public function checkWallet(WalletRepository $wallet_repository, string $wallet): JsonResponse
    {
        return response()->json($wallet_repository->exist('address', $wallet));

    }

    /**
     * @return JsonResponse
     */
    public function getCountInvited(): JsonResponse
    {
        $reit_collection = DB::table('users')
            ->Where('users.id', '>', 1)
            ->select('users.contract_user_id', 'users.user_name', 'users.id', DB::raw("count(u.id) as count"),)
            ->join('users as u', function ($join) {
                $join->on('users.contract_user_id', '=', 'u.this_referral')
                    ->whereBetween('u.created_at', [now()->startOfMonth(), now()]);
            })
            ->groupBy('users.contract_user_id', 'users.user_name', 'users.id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        return response()->json($reit_collection);

    }

    /**
     * @param  int  $contract_user_id
     *
     * @return UserResource
     * @throws \ReflectionException
     */
    public function getUserByContractId(GetUserByContractIdRequest $request): UserResource
    {
        return new UserResource($this->userRepository->findByOrFail('contract_user_id', $request->input('contract_user_id')));

    }

    /**
     * @param  GetUserByReferralRequest  $query
     *
     * @return UserResource
     */
    public function getUserByReferral(GetUserByReferralRequest $query): UserResource
    {
        return $this->userRepository->getUserByReferralLink($query->input('referral_link'));
    }

    /**
     * @param  IndexAdminUsersRequest  $request
     *
     * @return AnonymousResourceCollection
     */
    public function indexAdmin(IndexAdminUsersRequest $request): AnonymousResourceCollection
    {
        $q = User::query()->orderBy('id');
        if ($request->filled('searchByContractId')) {
            $q->where('contract_user_id', 'LIKE', '%' . $request->input('searchByContractId') . '%');
        }
        return UserResource::collection($q->paginate($request->per_page));

    }

    /**
     * @param  CabinetService  $cabinetService
     *
     * @return JsonResponse
     */
    public function getCommonInfo(CabinetService $cabinetService): JsonResponse
    {
        [$all_count_users, $users_invited_last_24_hour, $all_trx, $common_count_profit_referrals, $common_count_profit_reinvest] = $cabinetService->mainAdminInfo();
        return response()->json(compact(
            'all_count_users',
            'users_invited_last_24_hour',
            'all_trx',
            'common_count_profit_referrals',
            'common_count_profit_reinvest'
        ));
    }
}
