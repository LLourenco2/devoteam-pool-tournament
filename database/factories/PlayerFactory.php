<?php

namespace Database\Factories;

use App\Jobs\FetchAvatar;
use App\Models\Player;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = $this->faker->randomElement(['male', 'female']);
        $name = $this->faker->name($gender);

        return [
            'name' => $name,
            'gender' => $gender,
            'avatar' => null,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Player $player) {
            FetchAvatar::dispatch($player);
        });
    }
}
