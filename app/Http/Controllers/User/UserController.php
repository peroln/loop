<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Models\Users;
use App\MultiplePaginate;
use App\Http\Requests\User\GetAllUserRequest;

class UserController extends Controller
{
    use MultiplePaginate;

    public function getAllUsers(GetAllUserRequest $request)
    {
        return UserResource::collection(Users::paginate($request->per_page));
    }

    public function getUserById($id)
    {
        return UserResource::collection(Users::where('id', $id)->get());
    }
}
