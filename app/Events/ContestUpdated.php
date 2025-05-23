<?php

namespace App\Events;

use App\Http\Resources\ContestResource;
use App\Models\Contest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ContestUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
  
    protected $contest;

    /**
     * Create a new event instance.
     */
    public function __construct(Contest $contest)
    {
        $this->contest = $contest;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
       return new Channel('tournament.' . $this->contest->tournament_id);
    }

    public function broadcastWith()
    {
        return (new ContestResource($this->contest))->toArray(request());
    }
}
