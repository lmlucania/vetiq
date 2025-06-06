<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Review\Enum\Rating;
use App\Models\HospitalModel;
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
            'hospital_id' => HospitalModel::factory()->create()->id,
            'user_id'     => User::factory()->create()->id,
            'rating'      => Rating::One,
            'title'       => $this->faker->sentence,
            'body'        => $this->faker->paragraph,
        ];
    }
}
