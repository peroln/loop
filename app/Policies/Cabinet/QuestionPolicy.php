<?php

namespace App\Policies\Cabinet;

use App\Models\Cabinet\Question;
use App\Models\Wallet;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    /**
     * @param  Wallet  $wallet
     *
     *
     * @return bool
     */
    public function before(?Wallet $wallet)
    {
        if($wallet){
            return $wallet->user()->role_id === 1;
        }

    }

    /**
     * Determine whether the user can view any models.
     *
     *
     * @return bool
     */
    public function viewAny(?Wallet $wallet): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function view(?Wallet $wallet)
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
     * @param  \App\Models\Wallet            $wallet
     * @param  \App\Models\Cabinet\Question  $question
     *
     * @return mixed
     */
    public function update(Wallet $wallet, Question $question)
    {
        if ($wallet->user->blackList()->exists()) {
            return false;
        };
        return $wallet->user_id === $question->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Wallet            $wallet
     * @param  \App\Models\Cabinet\Question  $question
     *
     * @return mixed
     */
    public function delete(Wallet $wallet, Question $question)
    {
        if ($wallet->user->blackList()->exists()) {
            return false;
        };
        return $wallet->user_id === $question->user_id;
    }
}
