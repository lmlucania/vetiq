<?php

declare(strict_types=1);

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class HospitalModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $address = $this->faker->address();  // 例）1234567 東京都新宿区西新宿2-8-1
        return [
            'uuid'         => Str::uuid(),
            'name'         => $this->faker->firstName() . '病院',
            'zipcode'      => $this->faker->postcode(),
            'address'      => mb_substr($address, 9),
            'phone'        => '0' . $this->faker->numberBetween(100000000, 9999999999),
            'is_published' => true,
        ];
    }
}
