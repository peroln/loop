<?php

namespace App\Events;

use App\Models\Wallet;
use App\Models\Service\Overflow as ModelOverflow;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Overflow implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    private Wallet $wallet;
    private ModelOverflow $overflow;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Wallet $wallet, ModelOverflow $overflow)
    {
        //
        $this->wallet = $wallet;
        $this->overflow = $overflow;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('wallet.' . $this->wallet->id);
    }
    public function broadcastWith(): array
    {
        return [
            'data' => [
                'overflow' => 'true',
                'platform_level' => $this->overflow->platformLevel->name
            ],
        ];
    }
    public function broadcastAs(): string
    {
        return 'OverflowEvent';
    }
}
