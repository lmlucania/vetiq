<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Pet\Enum\Gender;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pet>
 */
class PetFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid'            => (string) Str::uuid(),
            'gender'          => Gender::Male,
            'birthday'        => $this->faker->dateTimeBetween('-15 years', '-1 year'),
            'started_care_at' => $this->faker->dateTimeBetween('-10 years', 'now'),
            'name'            => $this->faker->firstName,
            'remark'          => $this->faker->optional()->sentence(),
        ];
    }
}
