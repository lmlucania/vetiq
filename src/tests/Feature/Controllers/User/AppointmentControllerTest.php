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
use Carbon\Carbon;
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
        $statusHistory = AppointmentStatusHistory::factory()->create([
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
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'id' => $appointment->id,
                'status' => $statusHistory->status,
            ]);
    }

    /**
     * 予約の登録
     */
    public function testStoreSuccess()
    {
        // Arrange（準備）
        $this->actingAs($this->user, $this->guard);

        $hospital = Hospital::factory()->create();
        $menu = Menu::factory()->create(['hospital_id' => $hospital->id]);
        $vet = Vet::factory()->create(['hospital_id' => $hospital->id]);
        $postData = [
            'pet_id'         => $this->pet->id,
            'hospital_id'    => $hospital->id,
            'menu_id'        => $menu->id,
            'vet_id'         => $vet->id,
            'appointment_at' => Carbon::now()->addDay()->toISOString(),
        ];

        // Act（実行）
        $response = $this->post(route('user.appointments.store'), $postData);

        // Assert（検証）
        $response->assertStatus(200);

        $this->assertDatabaseCount('appointments', 1);
        $this->assertDatabaseCount('appointment_status_histories', 1);

        $appointment = Appointment::first();
        $this->assertEquals($postData['pet_id'], $appointment->pet_id);
        $this->assertEquals($postData['hospital_id'], $appointment->hospital_id);
        $this->assertEquals($postData['menu_id'], $appointment->menu_id);
        $this->assertEquals($postData['vet_id'], $appointment->vet_id);

        $statusHistory = AppointmentStatusHistory::first();
        $this->assertEquals($appointment->id, $statusHistory->appointment_id);
        $this->assertEquals(AppointmentStatus::Reserved, $statusHistory->status);
    }

    /**
     * 予約の詳細
     */
    public function testShowSuccess()
    {
        // Arrange（準備）
        $this->actingAs($this->user, $this->guard);
        $appointment = Appointment::factory()->create([
            'pet_id'      => $this->pet,
            'hospital_id' => $this->hospital,
            'menu_id'     => Menu::factory()->create(['hospital_id' => $this->hospital->id]),
            'vet_id'         => Vet::factory()->create(['hospital_id' => $this->hospital->id]),
            'appointment_at' => '2025-01-01 09:00',
        ]);
        AppointmentStatusHistory::factory()->create([
            'id' => 1,
            'appointment_id' => $appointment->id,
            'status'         => AppointmentStatus::Reserved,
            'modifier_type' => '',
            'modifier_id' => 1
        ]);
        AppointmentStatusHistory::factory()->create([
            'id' => 2,
            'appointment_id' => $appointment->id,
            'status'         => AppointmentStatus::Cancelled,
            'modifier_type' => '',
            'modifier_id' => 1
        ]);

        // Act（実行）
        $response = $this->get(route('user.appointments.show', ['appointment' => $appointment->id]));

        // Assert（検証）
        $response
            ->assertStatus(200)
            ->assertJsonCount(9, 'data')
            ->assertJsonFragment([
                'id'          => $appointment->id,
                'appointment_at'          => '2025-01-01 09:00',
            ])
            // 新着順でステータスが取得されていることを確認する
            ->assertJsonPath('data.status_histories.data.0.status', AppointmentStatus::Reserved->value)
            ->assertJsonPath('data.status_histories.data.1.status', AppointmentStatus::Cancelled->value);
    }

    /**
     * 予約のキャンセル
     */
    public function testCancelSuccess()
    {
        // Arrange（準備）
        $this->actingAs($this->user, $this->guard);
        $appointment = Appointment::factory()->create([
            'pet_id'      => $this->pet,
            'hospital_id' => $this->hospital,
            'menu_id'     => Menu::factory()->create(['hospital_id' => $this->hospital->id]),
            'vet_id'         => Vet::factory()->create(['hospital_id' => $this->hospital->id]),
            'appointment_at' => '2030-01-01 09:00',
        ]);
        AppointmentStatusHistory::factory()->create([
            'id' => 1,
            'appointment_id' => $appointment->id,
            'status'         => AppointmentStatus::Reserved,
            'modifier_type' => '',
            'modifier_id' => 1
        ]);

        // Act（実行）
        $response = $this->patch(route('user.appointments.cancel', ['id' => $appointment->id]));

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('appointment_status_histories', 2);

        $statusHistory = AppointmentStatusHistory::latest()->first();
        $this->assertEquals(AppointmentStatus::Cancelled, $statusHistory->status);
    }
}
