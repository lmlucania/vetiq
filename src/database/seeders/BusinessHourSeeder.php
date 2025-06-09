<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\BusinessHourModel;
use App\Models\Hospital;
use Illuminate\Database\Seeder;

class BusinessHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hospitals = Hospital::all();
        foreach ($hospitals as $hospital) {
            BusinessHourModel::factory()->create(['hospital_id' => $hospital->id]);
        }
    }
}
