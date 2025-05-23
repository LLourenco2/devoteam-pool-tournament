<?php

namespace App\Http\Controllers;

use App\ContestStatus;
use App\Http\Resources\ContestResource;
use App\Models\Contest;

class ContestController extends Controller
{
    public function index() {
        $query = Contest::query();
        self::filter($query);
        return response()->json(ContestResource::collection($query->get()));
    }

    public function filter(&$query){
        $filters = json_decode(request('filters'));
   
        if (isset($filters)) {
            if (isset($filters->tournament)) {
                $query->where('tournament_id', $filters->tournament);
            }
        }
    }


    public static function generateContests($tournamentId, $playerIds){
        $playerIds = $playerIds->values();

        $contestsToInsert  = [];
        $countPlayerIds = $playerIds->count();
        for ($i = 0; $i < $countPlayerIds; $i++) {
            for ($j = $i + 1; $j < $countPlayerIds; $j++) {
                $playerA = $playerIds[$i];
                $playerB = $playerIds[$j];

                $contestsToInsert[] = [
                    'tournament_id' => $tournamentId,
                    'player1_id' => $playerA,
                    'player2_id' => $playerB,
                    'status' => ContestStatus::Scheduled,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
    
        Contest::insert($contestsToInsert);
    }

    public function show(Contest $contest) {
        return response()->json(new ContestResource($contest));
    }
}
