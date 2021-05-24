<?php declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class PingEvent
 * @package App\Events\Broadcast\Debug
 */
class PingEvent implements ShouldBroadcast
{
    public function broadcastOn()
    {
        return new PrivateChannel('debug');
    }

    public function broadcastWith(): array
    {
        return [
            'data' => [
                'ping' => 'hello my friend',
            ],
        ];
    }

    public function broadcastAs(): string
    {
        return 'TestEvent';
    }
}
