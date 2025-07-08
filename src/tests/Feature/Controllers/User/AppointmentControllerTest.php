<?php

declare(strict_types=1);

namespace Feature\Controllers\User;

use App\Domains\Appointment\Enum\AppointmentStatus;
use App\Models\Appointment;
use App\Models\AppointmentStatusHistory;
use App\Models\Hospital;
use App\Models\Menu;
use App\Models\Pet;
use App\Models\User;
use App\Models\Vet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AppointmentControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $guard = 'users';

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->pet  = Pet::factory()->create([
            'user_id' => $this->user,
        ]);
        $this->hospital = Hospital::factory()->create();
    }

    /**
     * 予約の一覧
     */
    public function testIndexSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        $appointment = Appointment::factory()->create([
            'pet_id'      => $this->pet,
            'hospital_id' => $this->hospital,
            'menu_id'     => Menu::factory()->create(['hospital_id' => $this->hospital->id]),
            'vet_id'         => Vet::factory()->create(['hospital_id' => $this->hospital->id]),
        ]);
        AppointmentStatusHistory::factory()->create([
            'appointment_id' => $appointment->id,
            'status'         => AppointmentStatus::Reserved,
            'modifier_type' => '',
            'modifier_id' => 1
        ]);
        $otherHospital = Hospital::factory()->create();
        Appointment::factory()->create([ // 他ユーザーの予約
            'pet_id'      => Pet::factory()->create(['user_id' => User::factory()->create()]),
            'hospital_id' => $otherHospital,
            'menu_id'     => Menu::factory()->create(['hospital_id' => $otherHospital]),
        ]);

        // 実行（Act）
        $response = $this->get(route('user.appointments.index'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(1, 'data');
    }
}
