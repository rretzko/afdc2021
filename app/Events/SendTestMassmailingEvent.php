<?php

namespace App\Events;

use App\Models\Massmailing;
use App\Models\Person;
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
    public $person;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Massmailing $massmailing, Person $person)
    {
        $this->massmailing = $massmailing;
        $this->emailto = $this->massmailing->findVar('sender_email');
        $this->person= $person;
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
