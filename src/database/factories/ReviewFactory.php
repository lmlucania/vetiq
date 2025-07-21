<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Review\Enum\Rating;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hospital_id' => Hospital::inRandomOrder()->first(),
            'user_id'     => User::inRandomOrder()->first(),
            'rating'      => Rating::One,
            'title'       => $this->faker->realText(10),
            'body'        => $this->faker->realText(),
        ];
    }
}
