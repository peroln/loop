<?php

namespace App\Policies\Common;

use App\Models\Common\Article;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * @param  Model|null  $model
     *
     * @return bool
     */
    public function before(?Model $model)
    {
        if ($model && get_class($model) === User::class) {
            if ($model->role_id === 1) {
                return true;
            }
        } else if ($model && get_class($model) === Wallet::class) {
            if ($model->user->role_id === 1) {
                return true;
            }
        }
    }

    /**
     * @param  User  $user
     *
     * @return bool
     */
    public function viewAny(?Model $model)
    {
        return  true;
    }

    /**
     * @param  User     $user
     * @param  Article  $article
     *
     * @return bool
     */
    public function view( Article $article, ?Model $model)
    {
        return true;
    }

    /**
     * @param  User  $user
     *
     * @return bool
     */
    public function create(?Model $model,)
    {
        return $model?->role_id === 1;
    }

    /**
     * @param  User     $user
     * @param  Article  $article
     *
     * @return bool
     */
    public function update(Article $article, ?Model $model)
    {
        return $model?->role_id === 1;
    }

    /**
     * @param  User     $user
     * @param  Article  $article
     *
     * @return bool
     */
    public function delete(Article $article, ?Model $model)
    {
        return $model?->role_id === 1;
    }

}
