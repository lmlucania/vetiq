<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Appointment\Enum\AppointmentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppointmentStatusHistory>
 */
class AppointmentStatusHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status'        => AppointmentStatus::Reserved,
            'hospital_memo' => $this->faker->realText(),
        ];
    }
}
