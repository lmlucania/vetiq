<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vet>
 */
class VetFactory extends Factory
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
            'hospital_id'        => Hospital::factory()->create()->id,
            'last_name'          => $this->faker->lastName(),
            'first_name'         => $this->faker->firstName(),
            'accept_appointment' => true,
            'remark'             => $this->faker->realText(),
        ];
    }
}
