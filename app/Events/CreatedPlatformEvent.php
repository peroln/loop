<?php

namespace App\Events;

use App\Models\Service\Platform;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreatedPlatformEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Platform $platform;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Platform $platform)
    {
        //
        $this->platform = $platform;
        Log::info($platform->wallet_id . ' - ' . $platform->platform_level_id);

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
