<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Hospital;

use App\Domains\Schedule\Enum\DayOfWeek;
use App\Domains\Schedule\Enum\TimePeriod;
use App\Models\BusinessHour;
use App\Models\Hospital;
use App\Models\StaffMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BusinessHourControllerTest extends TestCase
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
        BusinessHour::factory()->create([
            'hospital_id' => $this->hospital->id,
            'day_of_week' => DayOfWeek::SUNDAY,
            'time_period' => TimePeriod::AM,
            'start_time'  => '09:00',
            'end_time'    => '12:00',
        ]);
        BusinessHour::factory()->create([
            'hospital_id' => $this->hospital->id,
            'day_of_week' => DayOfWeek::SUNDAY,
            'time_period' => TimePeriod::PM,
            'start_time'  => '13:00',
            'end_time'    => '16:00',
        ]);
        BusinessHour::factory()->create([
            'hospital_id' => $this->hospital->id,
            'day_of_week' => DayOfWeek::MONDAY,
            'time_period' => TimePeriod::AM,
            'start_time'  => '10:00',
            'end_time'    => '13:00',
        ]);

        // ログインしない病院のデータをセットアップ
        $nonLoginHospital = Hospital::factory()->create();
        BusinessHour::factory()->create([
            'hospital_id' => $nonLoginHospital->id,
        ]);

        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.business_hours.index'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonFragment(
                [
                    'day_of_week' => DayOfWeek::SUNDAY,
                    'time_period' => TimePeriod::AM,
                    'start_time'  => '09:00',
                    'end_time'    => '12:00',
                ],
            )
            ->assertJsonFragment(
                [
                    'day_of_week' => DayOfWeek::SUNDAY,
                    'time_period' => TimePeriod::PM,
                    'start_time'  => '13:00',
                    'end_time'    => '16:00',
                ],
            )
            ->assertJsonFragment(
                [
                    'day_of_week' => DayOfWeek::MONDAY,
                    'time_period' => TimePeriod::AM,
                    'start_time'  => '10:00',
                    'end_time'    => '13:00',
                ],
            );
    }

    /**
     * 予約受付時間の一覧 0件の場合にエラーにならないこと
     */
    public function testIndexNoRecordSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.business_hours.index'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    /**
     * 指定した曜日の受付時間の作成/更新 作成されること
     */
    public function testStoreSuccess()
    {
        // 準備（Arrange）
        $postData = [
            'day_of_week' => DayOfWeek::FRIDAY->value,
            'periods'     => [
                [
                    'time_period' => TimePeriod::AM->value,
                    'start_time'  => '09:00',
                    'end_time'    => '12:30',
                ],
                [
                    'time_period' => TimePeriod::PM->value,
                    'start_time'  => '13:00',
                    'end_time'    => '19:30',
                ],
            ],
        ];
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->post(route('hospital.business_hours.store'), $postData);

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('business_hours', 2);
    }

    /**
     * 指定した曜日の受付時間の作成/更新 作成と更新されること
     */
    public function testStoreCreateRecordAndUpdateRecordSuccess()
    {
        // 準備（Arrange）
        BusinessHour::factory()->create([
            'hospital_id' => $this->hospital->id,
            'day_of_week' => DayOfWeek::FRIDAY,
            'time_period' => TimePeriod::AM,
            'start_time'  => '10:00',
            'end_time'    => '13:00',
        ]);
        $postData = [
            'day_of_week' => DayOfWeek::FRIDAY->value,
            'periods'     => [
                [
                    'time_period' => TimePeriod::AM->value,
                    'start_time'  => '09:00',
                    'end_time'    => '12:30',
                ],
                [
                    'time_period' => TimePeriod::PM->value,
                    'start_time'  => '13:00',
                    'end_time'    => '19:30',
                ],
            ],
        ];
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->post(route('hospital.business_hours.store'), $postData);

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('business_hours', 2);

        $records = BusinessHour::orderBy('id', 'asc')->get();
        $this->assertEquals(DayOfWeek::FRIDAY, $records[0]->day_of_week);
        $this->assertEquals(TimePeriod::AM, $records[0]->time_period);
        $this->assertEquals('09:00', $records[0]->start_time->format('H:i'));
        $this->assertEquals('12:30', $records[0]->end_time->format('H:i'));

        $this->assertEquals(DayOfWeek::FRIDAY, $records[1]->day_of_week);
        $this->assertEquals(TimePeriod::PM, $records[1]->time_period);
        $this->assertEquals('13:00', $records[1]->start_time->format('H:i'));
        $this->assertEquals('19:30', $records[1]->end_time->format('H:i'));
    }

    /**
     * 指定した曜日の受付時間の作成/更新 作成と削除されること
     */
    public function testStoreCreateRecordAndDeleteRecordSuccess()
    {
        // 準備（Arrange）
        BusinessHour::factory()->create([
            'hospital_id' => $this->hospital->id,
            'day_of_week' => DayOfWeek::FRIDAY,
            'time_period' => TimePeriod::AM,
            'start_time'  => '10:00',
            'end_time'    => '13:00',
        ]);
        $postData = [
            'day_of_week' => DayOfWeek::FRIDAY->value,
            'periods'     => [
                [
                    'time_period' => TimePeriod::PM->value,
                    'start_time'  => '13:00',
                    'end_time'    => '19:30',
                ],
            ],
        ];
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->post(route('hospital.business_hours.store'), $postData);

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('business_hours', 1);

        $record = BusinessHour::first();
        $this->assertEquals(DayOfWeek::FRIDAY, $record->day_of_week);
        $this->assertEquals(TimePeriod::PM, $record->time_period);
        $this->assertEquals('13:00', $record->start_time->format('H:i'));
        $this->assertEquals('19:30', $record->end_time->format('H:i'));
    }

    /**
     * 予約受付時間の削除 削除できていること
     */
    public function testDestroySuccess()
    {
        // 準備（Arrange）
        $model = BusinessHour::factory()->create([
            'hospital_id' => $this->hospital->id,
            'day_of_week' => DayOfWeek::FRIDAY,
            'time_period' => TimePeriod::AM,
            'start_time'  => '10:00',
            'end_time'    => '13:00',
        ]);
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->delete(route('hospital.business_hours.destroy', ['business_hour' => $model->id]));

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('business_hours', 0);
    }

    /**
     * 予約受付時間の削除 他の病院の予約受付時間を削除できないこと
     */
    public function testDestroyNotHospitalOwnFailure()
    {
        // 準備（Arrange）
        $model = BusinessHour::factory()->create([
            'hospital_id' => Hospital::factory()->create()->id,
            'day_of_week' => DayOfWeek::FRIDAY,
            'time_period' => TimePeriod::AM,
            'start_time'  => '10:00',
            'end_time'    => '13:00',
        ]);
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->delete(route('hospital.business_hours.destroy', ['business_hour' => $model->id]));

        // 検証（Assert）
        $response->assertStatus(404);
    }
}
