<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\HospitalModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Favorite>
 */
class FavoriteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'     => User::factory()->create()->id,
            'hospital_id' => HospitalModel::factory()->create()->id,
        ];
    }
}
