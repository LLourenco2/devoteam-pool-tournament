<?php

namespace App\Jobs;

use App\ContestStatus;
use App\Events\TournamentUpdated;
use App\Models\Contest;
use App\Models\Tournament;
use Illuminate\Bus\Batch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class SimulateTournament implements ShouldQueue
{
    use Queueable;

    protected $tournament;

    /**
     * Create a new job instance.
     */
    public function __construct(Tournament $tournament)
    {
        $this->tournament = $tournament;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $contests = Contest::where('tournament_id', $this->tournament->id)
            ->whereIn('status', [ContestStatus::Scheduled, ContestStatus::Ongoing])
            ->get();

        $chain = [];

        foreach ($contests as $contest) {
            $chain[] = new SimulateContest($contest);
        }

        if (!empty($chain)) {
            $tournament = $this->tournament;
            Bus::batch([$chain])
                ->then(function () use ($tournament) {
                    try {
                        broadcast(new TournamentUpdated($tournament));
                    } catch (\Throwable $e) {
                        Log::error("Broadcast failed (Tournament update): " . $e->getMessage());
                    }
                    Log::info('All chained jobs completed.');
                })
                ->catch(function (\Throwable $e) {
                    Log::error("Job chain failed: " . $e->getMessage());
                })->dispatch();
        }
    }
}
