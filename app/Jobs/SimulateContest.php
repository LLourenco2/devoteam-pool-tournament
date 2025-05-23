<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use App\ContestStatus;
use App\Events\ContestUpdated;
use App\Models\Contest;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SimulateContest implements ShouldQueue
{
    use Queueable, Batchable;

    public $contest;

    /**
     * Create a new job instance.
     */
    public function __construct(Contest $contest)
    {
        $this->contest = $contest;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->contest->update(['status' => ContestStatus::Ongoing]);

        try {
            broadcast(new ContestUpdated($this->contest->fresh(['player1', 'player2', 'winner'])));
        } catch (\Throwable $e) {
            Log::error("Broadcast failed (Ongoing update): " . $e->getMessage());
        }

        sleep(5);

        $players = [$this->contest->player1_id, $this->contest->player2_id];
        $winnerId = $players[array_rand($players)];
        $ballsLeft = rand(1, 7);

        $this->contest->update([
            'winner_id' => $winnerId,
            'loser_balls_left' => $ballsLeft,
            'status' => ContestStatus::Ended,
        ]);

        try {
            broadcast(new ContestUpdated($this->contest->fresh(['player1', 'player2', 'winner'])));
        } catch (\Throwable $e) {
            Log::error("Broadcast failed (Contest update): " . $e->getMessage());
        }

        CalculateLeaderboard::dispatch($this->contest->tournament_id);
    }
}
