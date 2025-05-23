<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlayerTournamentStatsResource extends JsonResource
{
    private $player;
    private $contests;
    private $stats;
    private $tournament;
    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($tournament, $player, $contests, $stats)
    {
        $this->player = $player;
        $this->contests = $contests;
        $this->stats = $stats;
        $this->tournament = $tournament;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'tournament' => new TournamentResource($this->tournament),
            'player' => new PlayerResource($this->player), //->append('avatar_url')->only(['id','name', 'avatar_url']),
            'contests' => ContestResource::collection($this->contests), //->load(['player1', 'player2', 'winner']),
            'stats' => $this->stats
        ];
    }
}
