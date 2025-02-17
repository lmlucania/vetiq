<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VetModel>
 */
class VetModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid'               => Str::uuid(),
            'last_name'          => $this->faker->lastName(),
            'first_name'         => $this->faker->firstName(),
            'accept_appointment' => true,
            'remark'             => $this->faker->realText(),
        ];
    }
}
