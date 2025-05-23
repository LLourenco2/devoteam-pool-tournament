<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlayerController extends Controller
{
    // public function store(TournamentRequest $request) {
    //     $validated = $request->validated();

    //     $tournament = Tournament::create($validated);

    //     $numLocalPlayers = round($validated['numberPlayers'] * 0.8);

    //     $players = Player::inRandomOrder()->limit($numLocalPlayers)->get();

    //     if ($players->count() < $numLocalPlayers ) {
            
    //     }

    //     ContestController::generateContests($tournament->id, $playerIds);
    // }
}
