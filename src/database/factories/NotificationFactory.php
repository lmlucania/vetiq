<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid'         => (string) Str::uuid(),
            'hospital_id'  => Hospital::inRandomOrder()->first(),
            'title'        => $this->faker->realText(10),
            'detail'       => $this->faker->realText(),
            'is_published' => $this->faker->boolean(),
            'published_at' => $this->faker->optional()->date(),
        ];
    }
}
