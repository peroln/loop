<?php

namespace App\Listeners;

use App\Events\ReactivationPlatform;
use App\Events\UserCreatedEvent;
use App\Models\User;
use App\Models\User\Status;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ReactivationUserStatusCounter
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  ReactivationPlatform  $event
     * @return void
     */
    public function handle(ReactivationPlatform $event)
    {
        try {
            if (!$event->platform->wallet->user_id) {
                return;
            }
            $this->statusLevelCounter($event);
        } catch (\Throwable $e) {
            Log::info(__FILE__ . ' ' . $e->getMessage());
        }
    }

    /**
     * @param ReactivationPlatform $event
     */
    private function statusLevelCounter(ReactivationPlatform $event): void
    {
        $user = User::findOrFail($event->platform->wallet->user_id);
        $count_reactivation = $user->wallet->reactivations()->sum('count');

        $user_level = match (true) {
            $count_reactivation >= 500 => 5,
            $count_reactivation >= 250 => 4,
            $count_reactivation >= 100 => 3,
            $count_reactivation >= 10 => 2,
            default => 1
        };
        $status = Status::where('name', 'Miner')->firstOrFail();
        $old_model = $user->statuses()->wherePivot('status_id', $status->id)->first();
        if($old_model){
            $old_model->pivot->level = $user_level;
            $old_model->pivot->save();
        }else{
            $user->statuses()->attach($status->id, ['level' => $user_level]);
        }
    }
}
