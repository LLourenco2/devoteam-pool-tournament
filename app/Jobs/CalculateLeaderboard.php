<?php

namespace App\Jobs;

use App\Events\LeaderboardUpdated;
use App\Models\Tournament;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CalculateLeaderboard implements ShouldQueue
{
    use Queueable;

    protected $tournamentId;

    /**
     * Create a new job instance.
     */
    public function __construct($tournamentId)
    {
        $this->tournamentId = $tournamentId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            broadcast(new LeaderboardUpdated($this->tournamentId, Tournament::findOrFail($this->tournamentId)->leaderboard()));
        } catch (\Throwable $e) {
            Log::error("Broadcast failed (Leaderboard update): " . $e->getMessage());
        }
    }
}
