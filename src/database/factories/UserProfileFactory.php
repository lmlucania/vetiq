<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Location\Enum\Prefecture;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VetModel>
 */
class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'last_name'       => $this->faker->lastName(),
            'first_name'      => $this->faker->firstName(),
            'last_name_kana'  => $this->faker->lastKanaName(),
            'first_name_kana' => $this->faker->firstKanaName(),
            'phone'           => $this->faker->phoneNumber(),
            'post_code'       => $this->faker->postcode(),
            'prefecture'      => Prefecture::Hokkaido,
            'address1'        => $this->faker->streetAddress(),
            'address2'        => $this->faker->secondaryAddress(),
        ];
    }
}
