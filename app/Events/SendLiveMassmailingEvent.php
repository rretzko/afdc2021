<?php

namespace App\Events;

use App\Models\Massmailing;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendLiveMassmailingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $massmailing;
    public $recipients;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Massmailing $massmailing, Collection $recipients)
    {
        $this->massmailing = $massmailing;
        $this->recipients = $recipients;
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
