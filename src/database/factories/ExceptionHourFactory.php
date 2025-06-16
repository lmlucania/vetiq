<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Domains\Schedule\Enum\TimePeriod;
use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExceptionHour>
 */
class ExceptionHourFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'hospital_id' => Hospital::inRandomOrder()->first()->id,
            'date'        => '2025/02/01',
            'time_period' => TimePeriod::AM,
            'start_time'  => null,
            'end_time'    => null,
            'is_closed'   => true,
            'reason'      => '院長不在のため休診',
        ];
    }
}
