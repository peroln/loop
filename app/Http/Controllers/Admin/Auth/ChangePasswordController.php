<?php


namespace App\Http\Controllers\Admin\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserChangePasswordRequest;
use App\Models\User;
use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ChangePasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admins');
    }

    public function store(UserChangePasswordRequest $request)
    {
        User::find(auth()->guard('admins')->user()->id)->update(['password' => Hash::make($request->new_password)]);
        return response()->json('Password was changed successfully.');
    }
}
