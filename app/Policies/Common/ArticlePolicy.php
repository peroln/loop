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
     * @param  User  $user
     *
     * @return bool
     */
    public function viewAny(User $user)
    {
        return  true;
    }

    /**
     * @param  User     $user
     * @param  Article  $article
     *
     * @return bool
     */
    public function view(User $user, Article $article)
    {
        return true;
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
     * @param  User     $user
     * @param  Article  $article
     *
     * @return bool
     */
    public function update(User $user, Article $article)
    {
        return $user->role_id === 1;
    }

    /**
     * @param  User     $user
     * @param  Article  $article
     *
     * @return bool
     */
    public function delete(User $user, Article $article)
    {
        return $user->role_id === 1;
    }

    /**
     * eturn mixed
     */
    public function restore(User $user, Article $article)
    {
        //
    }

    /**
     * @param  User     $user
     * @param  Article  $article
     */
    public function forceDelete(User $user, Article $article)
    {
        //
    }
}
