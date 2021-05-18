<?php declare(strict_types=1);

namespace App\Events\Broadcast\Debug;

use App\Events\Broadcast\BroadcastEvent;
use App\Events\Broadcast\SocketEvents;
use Illuminate\Broadcasting\PrivateChannel;

/**
 * Class PingEvent
 * @package App\Events\Broadcast\Debug
 */
class PingEvent extends BroadcastEvent
{
    public function broadcastOn()
    {
        return new PrivateChannel('debug');
    }

    public function broadcastWith(): array
    {
        return [
            'data' => [
                'ping' => true,
            ],
        ];
    }

    public function broadcastAs(): string
    {
        return SocketEvents::PING_EVENT;
    }
}
