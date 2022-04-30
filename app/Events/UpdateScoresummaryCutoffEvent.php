<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateScoresummaryCutoffEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cutoff;
    public $eventversion_id;
    public $instrumentation_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($eventversion_id, $instrumentation_id, $cutoff)
    {
        $this->eventversion_id = $eventversion_id;
        $this->instrumentation_id = $instrumentation_id;
        $this->cutoff = $cutoff;
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
