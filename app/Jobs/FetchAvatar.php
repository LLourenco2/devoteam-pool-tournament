<?php

namespace App\Jobs;

use App\Models\Player;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class FetchAvatar implements ShouldQueue
{
    use Queueable;

    public $player;

    /**
     * Create a new job instance.
     */
    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        $url = env('AVATAR_API_URL', 'http://0.0.0.0') . "/public/" . ($this->player->gender === 'male' ? 'boy' : 'girl');

        $response = Http::get($url);

        if ($response->successful()) {
            $filename = Str::uuid() . '.png';

            Storage::put("avatars/original/{$filename}", $response->body());

            $manager = new ImageManager(new Driver());
            $resizedImage  = $manager->read($response->body())->resize(128, 128)->toPng();
            Storage::disk('public')->put("avatars/resized/{$filename}", (string) $resizedImage);

            $this->player->update(['avatar' => $filename]);
        }
    }
}
