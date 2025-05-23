<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaderboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'position' => $this->position,
            'player' =>  new PlayerResource($this->player),
            'score' => $this->score,
            'gamesWon' => $this->gamesWon,
            'gamesLost' => $this->gamesLost,
        ];
    }
}
