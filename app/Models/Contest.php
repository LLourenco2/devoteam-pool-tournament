<?php

namespace App\Models;

use App\ContestStatus;
use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    protected $fillable = [
        'tournament_id',
        'player1_id',
        'player2_id',
        'winner_id',
        'loser_balls_left',
        'status',
    ];

    protected $casts = [
        'status' => ContestStatus::class,
    ];

    public function tournament()
    {
        return $this->belongsTo(Tournament::class);
    }

    public function winner()
    {
        return $this->belongsTo(Player::class, 'winner_id');
    }

    public function player1()
    {
        return $this->belongsTo(Player::class, 'player1_id');
    }

    public function player2()
    {
        return $this->belongsTo(Player::class, 'player2_id');
    }
}
