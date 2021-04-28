<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
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
     * @param string $address
     * @return JsonResource
     */
    public function getUserByWallet(string $address): JsonResource
    {
        return UserResource::collection(User::whereHas('wallet', function ($query) use ($address) {
            $query->where('address', $address);
        })->get());

    }
}
