<?php

namespace App\Policies\Common;

use App\Models\Common\Article;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return mixed
     */
    public function viewAny()
    {
        return  true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Wallet  $wallet
     * @param  \App\Models\Common\Article  $article
     * @return mixed
     */
    public function view(Wallet $wallet, Article $article)
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
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Wallet  $wallet
     * @param  \App\Models\Common\Article  $article
     * @return mixed
     */
    public function update(Wallet $wallet, Article $article)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Wallet  $wallet
     * @param  \App\Models\Common\Article  $article
     * @return mixed
     */
    public function delete(User $user, Article $article)
    {
        return $user->role_id === 1;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Wallet  $wallet
     * @param  \App\Models\Common\Article  $article
     * @return mixed
     */
    public function restore(Wallet $wallet, Article $article)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Wallet  $wallet
     * @param  \App\Models\Common\Article  $article
     * @return mixed
     */
    public function forceDelete(Wallet $wallet, Article $article)
    {
        //
    }
}
