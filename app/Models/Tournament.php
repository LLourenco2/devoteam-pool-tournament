<?php

namespace App\Models;

use App\ContestStatus;
use App\TournamentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Tournament extends Model
{
    protected $fillable = [
        'name',
    ];

    protected $casts = [
        'status' => TournamentStatus::class,
    ];

    public function allPlayers()
    {
        $player1s = Player::whereIn('id', $this->contests()->pluck('player1_id'));
        $player2s = Player::whereIn('id', $this->contests()->pluck('player2_id'));

        return $player1s->union($player2s)->get();
    }

    public function contests()
    {
        return $this->hasMany(Contest::class);
    }

    public function getStatusAttribute(): TournamentStatus
    {
        $statuses = $this->contests()->pluck('status');

        if ($statuses->every(fn($status) => $status === ContestStatus::Ended)) {
            return TournamentStatus::Ended;
        }

        if ($statuses->contains(fn($status) => $status !== ContestStatus::Scheduled)) {
            return TournamentStatus::Ongoing;
        }

        return TournamentStatus::Open;
    }

    public function leaderboard(): Collection
    {
        $tournament = Tournament::findOrFail($this->id);

        $playerStats = [];

        foreach ($tournament->contests as $contest) {
            $p1 = $contest->player1_id;
            $p2 = $contest->player2_id;
            $winner = $contest->winner_id;

            foreach ([$p1, $p2] as $playerId) {
                if (!isset($playerStats[$playerId])) {
                    $playerStats[$playerId] = [
                        'player' => null,
                        'score' => 0,
                        'gamesWon' => 0,
                        'gamesLost' => 0,
                    ];
                }

                if ($playerId == $winner) {
                    $playerStats[$playerId]['score'] += 3;
                    $playerStats[$playerId]['gamesWon'] += 1;
                } elseif ($winner !== null) {
                    $playerStats[$playerId]['score'] += 1;
                    $playerStats[$playerId]['gamesLost'] += 1;
                }
            }
        }

        foreach ($playerStats as $id => &$stats) {
            $stats['player'] = Player::find($id);
        }

        return collect($playerStats)
            ->sortByDesc('score')
            ->values()
            ->map(function ($item, $index) {
                $item['position'] = $index + 1;
                return (object) $item;
            });
    }
    
}
