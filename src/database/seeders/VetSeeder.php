<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Hospital;
use App\Models\Vet;
use Illuminate\Database\Seeder;

class VetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hospitals = Hospital::all();
        foreach ($hospitals as $hospital) {
            Vet::factory(3)->create(['hospital_id' => $hospital->id]);
        }
    }
}
