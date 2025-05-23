<?php

namespace App\Http\Controllers;

use App\ContestStatus;
use App\Http\Requests\TournamentRequest;
use App\Http\Resources\IndexResponseResource;
use App\Http\Resources\LeaderboardResource;
use App\Http\Resources\PlayerResource;
use App\Http\Resources\PlayerTournamentStatsResource;
use App\Http\Resources\TournamentResource;
use App\Jobs\SimulateTournament;
use App\Models\Contest;
use App\Models\Player;
use Illuminate\Http\Request;
use App\Models\Tournament;
use App\TournamentStatus;

class TournamentController extends Controller
{
    public function index() {
        $query = Tournament::query();
        self::filter($query);
        $query->orderBy('id', 'desc');
        return response()->json(new IndexResponseResource($query->paginate(request('perPage'), ['*'], 'page', request('page')), TournamentResource::class));
    }

    public function filter(&$query){
        $filters = json_decode(request('filters'));
        if (isset($filters)) {
            if (isset($filters->status)) {
                switch ($filters->status) {
                    case TournamentStatus::Open->value:
                        $query->whereDoesntHave('contests', function ($q) {
                            $q->where('status', '!=', ContestStatus::Scheduled);
                        });
                        break;

                   case TournamentStatus::Ongoing->value:
                        $query->whereHas('contests', function ($q) {
                                $q->where('status', '!=', ContestStatus::Scheduled->value);
                            })->whereHas('contests', function ($q) {
                                $q->where('status', '!=', ContestStatus::Ended->value);
                            });
                        break;

                    case TournamentStatus::Ended->value:
                        $query->whereDoesntHave('contests', function ($q) {
                            $q->where('status', '!=', ContestStatus::Ended);
                        });
                        break;
                }
            }
        }
    }

    public function store(TournamentRequest $request) {
        $validated = $request->validated();
        $totalPlayers = $validated['numberPlayers'];

        $tournament = Tournament::create($validated);

        $localPlayers = floor($totalPlayers * 0.8);

        $players = Player::inRandomOrder()->limit($localPlayers)->get();
        $players = $players->concat(Player::factory()->count($totalPlayers - $players->count())->create());

        ContestController::generateContests($tournament->id, $players->pluck('id'));

        return response()->json(new TournamentResource($tournament));
    }

    public function simpleSimulate(Tournament $tournament) {
        if ($tournament->status != TournamentStatus::Open) {
            return response()->json(['message' => 'Tournament is already in progress or completed.'], 409);
        }
        SimulateTournament::dispatch($tournament);
        return response()->json(['message' => 'Simulation started.']);
    }

    public function show(Tournament $tournament) {
        return response()->json(new TournamentResource($tournament));
    }

    public function listPlayers(Tournament $tournament) {
        return response()->json(PlayerResource::collection($tournament->allPlayers()));
    }
    

    public function showPlayer(Tournament $tournament, Player $player) {

        if (!$tournament->allPlayers()->contains('id', $player->id)) {
            return response()->json(['message' => 'Player not found in this tournament.'], 404);
        }

        $contests = $player->contestsInTournament($tournament->id);

        $stats = [
            'wins' => $contests->where('winner_id', $player->id)->count(),
            'losses' => $contests->where('winner_id', '!=', null)->where('winner_id', '!=', $player->id)->count(),
            'total' => $contests->count(),
            'totalBallsLeft' => $contests->sum('loser_balls_left'),
        ];

        return response()->json(new PlayerTournamentStatsResource($tournament, $player,$contests, $stats)); 
    }
    
    public function leaderboard(Tournament $tournament)
    {
        return response()->json(LeaderboardResource::collection($tournament->leaderboard()));
    }
}
