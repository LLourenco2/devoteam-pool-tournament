<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tournament' => new TournamentResource($this->tournament),
            'player1' => new PlayerResource($this->player1),
            'player2' => new PlayerResource($this->player2),
            'winner' => isset($this->winner) ? new PlayerResource($this->winner) : null,
            'loserBallsLeft' => $this->loser_balls_left,
            'status' => $this->status,
        ];
    }
}
