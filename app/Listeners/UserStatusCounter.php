<?php

namespace App\Listeners;

use App\Events\UserCreatedEvent;
use App\Models\User;
use App\Models\User\Status;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class UserStatusCounter
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param UserCreatedEvent $event
     * @return void
     */
    public function handle(UserCreatedEvent $event): void
    {
        try {
            if (!$event->user->this_referral) {
                return;
            }
            $this->statusLevelCounter($event);
        } catch (\Throwable $e) {
            Log::info(__FILE__ . ' ' . $e->getMessage());
        }
    }

    /**
     * @param UserCreatedEvent $event
     */
    private function statusLevelCounter(UserCreatedEvent $event): void
    {
        $user_referral = User::findOrFail($event->user->this_referral);
        $referrals_count_subscribers = $user_referral->subscribers()->count();

        $user_level = match ($referrals_count_subscribers) {
            $referrals_count_subscribers >= 500 => 5,
            $referrals_count_subscribers >= 150 => 4,
            $referrals_count_subscribers >= 50 => 3,
            $referrals_count_subscribers >= 5 => 2,
            default => 1
        };
        $status = Status::where('name', 'Influencer')->firstOrFail();
        $user_referral->statuses()->sync([$status->id => ['level' => $user_level]]);
    }
}
