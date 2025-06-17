<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\AppointmentStatusHistory;
use App\Models\Pet;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pets = Pet::all();
        foreach ($pets as $pet) {
            AppointmentStatusHistory::factory()->create([
                'appointment_id' => Appointment::factory()->create(['pet_id' => $pet]),
                'modifier_type' => get_class($pet->user),
                'modifier_id' => $pet->user->id,
            ]);
        }
    }
}
