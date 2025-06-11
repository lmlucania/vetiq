<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Schedule\Enum\DayOfWeek;
use App\Domains\Schedule\Enum\TimePeriod;
use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BusinessHour>
 */
class BusinessHourFactory extends Factory
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
            'day_of_week' => DayOfWeek::SUNDAY,
            'time_period' => TimePeriod::AM,
            'start_time'  => '09:00',
            'end_time'    => '12:00',
        ];
    }
}
