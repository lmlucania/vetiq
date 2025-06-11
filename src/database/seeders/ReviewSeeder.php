<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Hospital;
use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hospitals = Hospital::all();
        foreach ($hospitals as $hospital) {
            Review::factory()->create([
                'hospital_id' => $hospital->id,
            ]);
        }
    }
}
