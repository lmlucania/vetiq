<?php

namespace Feature\Controllers\Hospital;

use App\Domains\Schedule\Enum\DayOfWeek;
use App\Domains\Schedule\Enum\TimePeriod;
use App\Models\ExceptionHour;
use App\Models\Hospital;
use App\Models\StaffMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExceptionHourControllerTest extends TestCase
{
    use RefreshDatabase;

    private $guard = 'staff-members';

    public function setUp():void
    {
        parent::setUp();

        // ログインする病院のデータをセットアップ
        $this->hospital = Hospital::factory()->create();
        $this->staff    = StaffMember::factory()->create([
            'hospital_id' => $this->hospital->id,
        ]);
    }

    /**
     * 予約受付時間の一覧 情報が取得できること
     */
    public function testIndexSuccess()
    {
        // 準備（Arrange）
        ExceptionHour::factory()->create([
            'hospital_id' => $this->hospital->id,
            'date' => '2025-01-01',
            'time_period' => TimePeriod::AM,
            'start_time'  => '09:00',
            'end_time'    => '12:00',
            'is_closed' => false,
            'reason' => 'テスト1',
        ]);
        ExceptionHour::factory()->create([
            'hospital_id' => $this->hospital->id,
            'date' => '2025-01-01',
            'time_period' => TimePeriod::PM,
            'start_time'  => '13:00',
            'end_time'    => '16:00',
            'is_closed' => false,
            'reason' => 'テスト2',
        ]);
        ExceptionHour::factory()->create([
            'hospital_id' => $this->hospital->id,
            'date' => '2025-01-02',
            'time_period' => TimePeriod::AM,
            'start_time'  => '10:00',
            'end_time'    => '13:00',
            'is_closed' => false,
            'reason' => 'テスト3',
        ]);

        // ログインしない病院のデータをセットアップ
        ExceptionHour::factory()->create([
            'hospital_id' => Hospital::factory()->create(),
        ]);

        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.exception-hours.index', ['year' => 2025]));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonFragment(
                [
                    'date' => '2025-01-01',
                    'time_period' => TimePeriod::AM,
                    'start_time'  => '09:00',
                    'end_time'    => '12:00',
                    'is_closed' => false,
                    'reason' => 'テスト1',
                ],
            )
            ->assertJsonFragment(
                [
                    'date' => '2025-01-01',
                    'time_period' => TimePeriod::PM,
                    'start_time'  => '13:00',
                    'end_time'    => '16:00',
                    'is_closed' => false,
                    'reason' => 'テスト2',
                ],
            )
            ->assertJsonFragment(
                [
                    'date' => '2025-01-02',
                    'time_period' => TimePeriod::AM,
                    'start_time'  => '10:00',
                    'end_time'    => '13:00',
                    'is_closed' => false,
                    'reason' => 'テスト3',
                ],
            );
    }

}
