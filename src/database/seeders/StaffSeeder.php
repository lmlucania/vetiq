<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\HospitalModel;
use App\Models\StaffModel;
use Illuminate\Database\Seeder;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hospitals = HospitalModel::all();
        foreach ($hospitals as $hospital) {
            StaffModel::factory()->count(10)->create(['hospital_id' => $hospital->id]);
        }
        StaffModel::factory()->create([
            'hospital_id' => $hospital->id,
            'email'       => 'staff+1@example.com',
        ]);
    }
}
