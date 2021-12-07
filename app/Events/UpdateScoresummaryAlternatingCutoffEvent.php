<?php

namespace App\Events;

use App\Models\Eventversion;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class UpdateScoresummaryAlternatingCutoffEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $eventensembles;
    public $eventversion;
    public $instrumentation_id;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Eventversion $eventversion, Collection $eventensembles, int $instrumentation_id)
    {
        $this->instrumentation_id = $instrumentation_id;
        $this->eventensembles = $eventensembles;
        $this->eventversion = $eventversion;
        $this->instrumentation_id = $instrumentation_id;
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
