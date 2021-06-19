<?php


namespace App\Http\Controllers\Admin\Auth;


use App\Http\Requests\Admin\UserRegistrationRequest;
use App\Http\Resources\User\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegistrationController
{
    /**
     * @param  UserRegistrationRequest  $request
     *
     * @return JsonResponse|UserResource
     */
    public function registration(UserRegistrationRequest $request): JsonResponse|UserResource
    {
        $user = User::whereHas('wallet', fn($q) => $q->where('address', $request->input('contract_address')))->firstOrFail();
        if ($user->email) {
            return response()->json('This user is registered already', 422);
        }
        $user->user_name = $request->input('user_name');
        $user->email     = $request->input('email');
        $user->password  = Hash::make($request->input('password'));
        $user->role_id   = $user->id === 1 ? Role::whereName('admin')->firstOrFail()->id : Role::whereName('user')->firstOrFail()->id;
        $user->save();
        return new UserResource($user);

    }
}
