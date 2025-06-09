<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Hospital;
use App\Models\MenuModel;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hospitals = Hospital::all();
        foreach ($hospitals as $hospital) {
            MenuModel::factory(3)->create(['hospital_id' => $hospital->id]);
        }
    }
}
