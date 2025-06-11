<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Hospital;
use App\Models\StaffMember;
use Illuminate\Database\Seeder;

class StaffMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hospitals = Hospital::all();
        foreach ($hospitals as $hospital) {
            StaffMember::factory()->count(3)->create(['hospital_id' => $hospital->id]);
        }
        // OpenAPIからログインできるようにデータを作成
        StaffMember::factory()->create([
            'hospital_id' => $hospital->id,
            'email'       => 'staff+1@example.com',
        ]);
    }
}
