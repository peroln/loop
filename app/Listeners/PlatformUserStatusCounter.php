<?php

namespace App\Listeners;

use App\Events\CreatedPlatformEvent;
use App\Events\MoneyTransactionEvent;
use App\Models\User\Status;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class PlatformUserStatusCounter
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
     * @param object $event
     * @return void
     */
    public function handle(CreatedPlatformEvent $event)
    {
        try {
            if (!$event->platform->wallet) {
                return;
            }
            $this->statusLevelCounter($event);
        } catch (\Throwable $e) {
            Log::info(__FILE__ . ' ' . $e->getMessage());
        }
    }

    private function statusLevelCounter(CreatedPlatformEvent $event): void
    {
        $wallet = $event->platform->wallet;
        $platforms = $wallet->platforms()->orderBy('created_at')->get()->unique('platform_level_id');
        $registration_time = $platforms->first()->created_at;
        $end_time = Carbon::create($registration_time)->addWeek(3)->toDateTimeString();
        $count = $platforms->whereBetween('created_at', [$registration_time, $end_time])->count();


        $user_level = match (true) {
            $count >= 15 => 5,
            $count >= 10 => 4,
            $count >= 5 => 3,
            $count >= 3 => 2,
            default => 1
        };
        $status = Status::where('name', 'Speedster')->firstOrFail();

        $old_model = $wallet->user->statuses()->wherePivot('status_id', $status->id)->first();
        if ($old_model) {
            $old_model->pivot->level = $user_level;
            $old_model->pivot->save();
        } else {
            $wallet->user->statuses()->attach($status->id, ['level' => $user_level]);
        }
    }
}
