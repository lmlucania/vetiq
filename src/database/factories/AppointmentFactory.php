<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Hospital;
use App\Models\Menu;
use App\Models\Pet;
use App\Models\Vet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $hospital = Hospital::inRandomOrder()->first();
        return [
            'pet_id'         => Pet::inRandomOrder()->first(),
            'hospital_id'    => $hospital,
            'menu_id'        => Menu::where('hospital_id', $hospital->id)->first(),
            'vet_id'         => Vet::where('hospital_id', $hospital->id)->first(),
            'appointment_at' => '2025-01-01 09:00',
        ];
    }
}
