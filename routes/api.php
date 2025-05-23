<?php

use App\Http\Controllers\ContestController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TournamentController;

Route::prefix('tournaments')->controller(TournamentController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/', 'store');
    Route::get('/{tournament}', 'show');
    Route::get('/{tournament}/leaderboard', 'leaderboard');
    Route::get('/{tournament}/players', 'listPlayers');
    Route::get('/{tournament}/players/{player}', 'showPlayer');
    Route::post('/{tournament}/simple-simulate', 'simpleSimulate');

});

Route::prefix('contests')->controller(ContestController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/{contest}', 'show');
});