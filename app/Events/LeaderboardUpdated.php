<?php

namespace App\Events;

use App\Http\Resources\LeaderboardResource;
use App\Models\Tournament;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LeaderboardUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $tournamentId;
    protected $leaderboard;

    /**
     * Create a new event instance.
     */
    public function __construct($tournamentId, $leaderboard)
    {
      $this->tournamentId = $tournamentId;
      $this->leaderboard = $leaderboard;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): Channel
    {
        return new Channel('tournament.' . $this->tournamentId);
    }

    public function broadcastWith()
    {
        return LeaderboardResource::collection($this->leaderboard)->toArray(request());
    }
}
