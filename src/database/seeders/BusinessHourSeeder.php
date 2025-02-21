<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\BusinessHourModel;
use App\Models\HospitalModel;
use Illuminate\Database\Seeder;

class BusinessHourSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hospitals = HospitalModel::all();
        foreach ($hospitals as $hospital) {
            BusinessHourModel::factory()->create(['hospital_id' => $hospital->id]);
        }
    }
}
