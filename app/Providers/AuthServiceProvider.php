<?php

namespace App\Providers;

use App\Models\Cabinet\{Answer, BlackList, Question};
use App\Policies\Cabinet\{AnswerPolicy, BlackListPolicy, QuestionPolicy};
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         Question::class => QuestionPolicy::class,
         Answer::class => AnswerPolicy::class,
         BlackList::class => BlackListPolicy::class,

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
