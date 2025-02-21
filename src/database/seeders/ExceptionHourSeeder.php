<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\ExceptionHourModel;
use App\Models\HospitalModel;
use Illuminate\Database\Seeder;

class ExceptionHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hospitals = HospitalModel::all();
        foreach ($hospitals as $hospital) {
            ExceptionHourModel::factory()->create(['hospital_id' => $hospital->id]);
        }
    }
}
