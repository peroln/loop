<?php

namespace App\Policies\Cabinet;

use App\Models\Cabinet\Answer;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AnswerPolicy
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
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\Wallet  $wallet
     *
     * @return mixed
     */
    public function viewAny(?Wallet $wallet)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Wallet          $wallet
     * @param  \App\Models\Cabinet\Answer  $answer
     *
     * @return mixed
     */
    public function view(?Wallet $wallet, Answer $answer)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Wallet  $wallet
     *
     * @return mixed
     */
    public function create(Wallet $wallet)
    {
        return !$wallet->user->blackList()->exists();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Wallet          $wallet
     * @param  \App\Models\Cabinet\Answer  $answer
     *
     * @return mixed
     */
    public function update(Wallet $wallet, Answer $answer)
    {
        if ($wallet->user->blackList()->exists()) {
            return false;
        };
        return $wallet->user_id === $answer->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Wallet          $wallet
     * @param  \App\Models\Cabinet\Answer  $answer
     *
     * @return mixed
     */
    public function delete(Wallet $wallet, Answer $answer)
    {
        if ($wallet->user->blackList()->exists()) {
            return false;
        };
        return $wallet->user_id === $answer->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Wallet          $wallet
     * @param  \App\Models\Cabinet\Answer  $answer
     *
     * @return mixed
     */
    public function restore(Wallet $wallet, Answer $answer)
    {

    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Wallet          $wallet
     * @param  \App\Models\Cabinet\Answer  $answer
     *
     * @return mixed
     */
    public function forceDelete(Wallet $wallet, Answer $answer)
    {

    }
}
