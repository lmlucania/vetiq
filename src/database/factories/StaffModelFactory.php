<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class StaffModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hospital_id' => Hospital::factory()->create()->id,
            'name'        => $this->faker->lastName() . $this->faker->firstName(),
            'email'       => $this->faker->unique()->safeEmail(),
            'password'    => Hash::make('password'),
        ];
    }
}
