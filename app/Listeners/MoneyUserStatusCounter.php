<?php

namespace App\Listeners;

use App\Events\MoneyTransactionEvent;
use App\Events\UserCreatedEvent;
use App\Models\User;
use App\Models\User\Status;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class MoneyUserStatusCounter
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
     * @param  MoneyTransactionEvent  $event
     *
     * @return void
     */
    public function handle(MoneyTransactionEvent $event)
    {
        try {
            if (!$event->user->wallet) {
                return;
            }
            $this->statusLevelCounter($event);
        } catch (\Throwable $e) {
            Log::info(__FILE__ . ' ' . $e->getMessage());
        }
    }

    private function statusLevelCounter(MoneyTransactionEvent $event): void
    {

        $users_amount = (int)$event->user->wallet->amount_transfers;

                $user_level = match (true) {
                    $users_amount >= 13500000000000 => 5,
                    $users_amount >= 1350000000000 => 4,
                    $users_amount >= 340000000000 => 3,
                    $users_amount >= 65000000000 => 2,
                    $users_amount >= 7000000000 => 1,
                    default => 0
                };
        $status     = Status::where('name', 'Moneymaker')->firstOrFail();
        $old_model  = $event->user->statuses()->wherePivot('status_id', $status->id)->first();
        if ($old_model) {
            $old_model->pivot->level = $user_level;
            $old_model->pivot->save();
        } else {
            $event->user->statuses()->attach($status->id, ['level' => $user_level]);
        }
    }
}
