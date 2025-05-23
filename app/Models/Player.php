<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
 
class Player extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'avatar',
    ];

    public function getAvatarUrlAttribute()
    {
        return $this->avatar
            ? asset("storage/avatars/resized/{$this->avatar}")
            : null;
    }

    public function contestsAsPlayer1()
    {
        return $this->hasMany(Contest::class, 'player1_id');
    }

    public function contestsAsPlayer2()
    {
        return $this->hasMany(Contest::class, 'player2_id');
    }

    public function contestsInTournament($tournamentId)
    {
        $contestsP1 = $this->contestsAsPlayer1()->where('tournament_id', $tournamentId)->get();
        $contestsP2 = $this->contestsAsPlayer2()->where('tournament_id', $tournamentId)->get();

        return $contestsP1->merge($contestsP2);
    }

}
