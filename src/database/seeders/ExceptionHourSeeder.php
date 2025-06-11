<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ExceptionHour;
use App\Models\Hospital;
use Illuminate\Database\Seeder;

class ExceptionHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hospitals = Hospital::all();
        foreach ($hospitals as $hospital) {
            ExceptionHour::factory()->create(['hospital_id' => $hospital->id]);
        }
    }
}
