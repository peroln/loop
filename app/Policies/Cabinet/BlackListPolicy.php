<?php

namespace App\Policies\Cabinet;

use App\Models\Cabinet\BlackList;
use App\Models\Wallet;
use Illuminate\Auth\Access\HandlesAuthorization;

class BlackListPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return mixed
     */
    public function viewAny(Wallet $wallet)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Wallet  $wallet
     * @param  \App\Models\Cabinet\BlackList  $blackList
     * @return mixed
     */
    public function view(Wallet $wallet, BlackList $blackList)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return mixed
     */
    public function create(Wallet $wallet)
    {
        return true;
    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Wallet  $wallet
     * @param  \App\Models\Cabinet\BlackList  $blackList
     * @return mixed
     */
    public function delete(Wallet $wallet, BlackList $blackList)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Wallet  $wallet
     * @param  \App\Models\Cabinet\BlackList  $blackList
     * @return mixed
     */
    public function restore(Wallet $wallet, BlackList $blackList)
    {

    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Wallet  $wallet
     * @param  \App\Models\Cabinet\BlackList  $blackList
     * @return mixed
     */
    public function forceDelete(Wallet $wallet, BlackList $blackList)
    {


    }
}
