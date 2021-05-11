<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\Wallet $wallet
     * @return mixed
     */
    public function viewAny(Wallet $wallet)
    {
        dd('any');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\Wallet $wallet
     * @param \App\Models\User $model
     * @return mixed
     */
    public function view(Wallet $wallet, User $model)
    {
        dd('view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\Wallet $wallet
     * @return mixed
     */
    public function create(Wallet $wallet)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\Wallet $wallet
     * @param \App\Models\User $model
     * @return mixed
     */
    public function update(Wallet $wallet, User $model)
    {
        return $wallet->user->id === $model->id  ? Response::allow()
            : Response::deny('You do not own this user.', 403);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\Wallet $wallet
     * @param \App\Models\User $model
     * @return mixed
     */
    public function delete(Wallet $wallet, User $model)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\Wallet $wallet
     * @param \App\Models\User $model
     * @return mixed
     */
    public function restore(Wallet $wallet, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\Wallet $wallet
     * @param \App\Models\User $model
     * @return mixed
     */
    public function forceDelete(Wallet $wallet, User $model)
    {
        //
    }
}
