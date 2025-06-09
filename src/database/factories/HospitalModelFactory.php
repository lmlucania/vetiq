<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Location\Enum\Prefecture;
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
            'phone'        => '0' . $this->faker->numberBetween(100000000, 9999999999),
            'post_code'    => $this->faker->postcode(),
            'prefecture'   => Prefecture::Hokkaido,
            'address1'     => $this->faker->streetAddress(),
            'address2'     => $this->faker->secondaryAddress(),
            'is_published' => true,
        ];
    }
}
