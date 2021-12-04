<?php

namespace App\Events;

use App\Models\Massmailing;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendTestMassmailingEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $emailto;
    public $massmailing;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Massmailing $massmailing)
    {
        $this->massmailing = $massmailing;
        $this->emailto = $this->massmailing->findVar('sender_email');
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
