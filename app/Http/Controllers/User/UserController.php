<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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

    public function getUserById($id)
    {
        return UserResource::collection(User::where('id', $id)->get());
    }

    /**
     * @param GetUserByWalletRequest $query
     * @return JsonResource
     */
    public function getUserByWallet(GetUserByWalletRequest $query): JsonResource
    {
        $address = $query->input('address');
        return UserResource::collection(User::whereHas('wallet', fn ($query) => $query->where('address', $address))->get());

    }
}
