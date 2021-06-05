<?php

namespace App\Providers;

use App\Events\MoneyTransactionEvent;
use App\Events\ReactivationPlatform;
use App\Events\UserCreatedEvent;
use App\Listeners\MoneyUserStatusCounter;
use App\Listeners\ReactivationUserStatusCounter;
use App\Listeners\UserStatusCounter;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserCreatedEvent::class => [
            UserStatusCounter::class
        ],
        ReactivationPlatform::class => [
           ReactivationUserStatusCounter::class
        ],
        MoneyTransactionEvent::class => [
            MoneyUserStatusCounter::class
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
