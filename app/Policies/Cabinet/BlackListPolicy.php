<?php

namespace App\Policies\Cabinet;

use App\Models\Cabinet\BlackList;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Access\HandlesAuthorization;

class BlackListPolicy
{
    use HandlesAuthorization;

    /**
     * @param  User  $user
     *
     * @return bool
     */
    public function viewAny(User $user)
    {
        return $user->role_id === 1;
    }

    /**
     * @param  User       $user
     * @param  BlackList  $blackList
     */
    public function view(User $user, BlackList $blackList)
    {
       return $user->role_id === 1;
    }

    /**
     * @param  User  $user
     *
     * @return bool
     */
    public function create(User $user)
    {
        return $user->role_id === 1;
    }


    /**
     * @param  User       $user
     * @param  BlackList  $blackList
     *
     * @return bool
     */
    public function delete(User $user, BlackList $blackList)
    {
        return $user->role_id === 1;
    }

    /**
     * @param  User       $user
     * @param  BlackList  $blackList
     */
    public function restore(User $user, BlackList $blackList)
    {

    }

    /**
     * @param  User       $user
     * @param  BlackList  $blackList
     */
    public function forceDelete(User $user, BlackList $blackList)
    {


    }
}
