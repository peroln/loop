<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\GetUserByIdRequest;
use App\Http\Requests\User\GetUserByWalletRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\MultiplePaginate;
use App\Http\Requests\User\GetAllUserRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\JsonResource;

class UserController extends Controller
{
    use MultiplePaginate;

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
        $address = $query->input('address');
        return new UserResource(User::whereHas('wallet', fn($query) => $query->where('address', $address))->first());
    }
}
