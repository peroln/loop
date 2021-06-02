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

class ReactivationPlatform implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Platform $platform;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Platform $platform)
    {
        //
        $this->platform = $platform;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('wallet.' . $this->platform->wallet_id);
//        return new PrivateChannel('debug');
    }

    public function broadcastWith(): array
    {
        return [
            'data' => [
                'reactivation' => 'true',
                'platform'     => $this->platform
            ],
        ];
    }

    public function broadcastAs(): string
    {
        return 'ReactivationEvent';
    }
}
