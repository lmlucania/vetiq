<?php

declare(strict_types=1);

namespace Feature\Controllers\Hospital;

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
     * 例外予約受付時間の一覧 情報が取得できること
     */
    public function testIndexSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->staff, $this->guard);
        ExceptionHour::factory()->create([
            'hospital_id' => $this->hospital->id,
            'date'        => '2025-01-01',
            'start_time'  => '09:00',
            'end_time'    => '12:00',
            'is_closed'   => false,
            'reason'      => 'テスト1',
        ]);
        ExceptionHour::factory()->create([
            'hospital_id' => $this->hospital->id,
            'date'        => '2025-01-01',
            'start_time'  => '13:00',
            'end_time'    => '16:00',
            'is_closed'   => false,
            'reason'      => 'テスト2',
        ]);
        ExceptionHour::factory()->create([
            'hospital_id' => $this->hospital->id,
            'date'        => '2025-01-02',
            'start_time'  => '10:00',
            'end_time'    => '13:00',
            'is_closed'   => false,
            'reason'      => 'テスト3',
        ]);

        // ログインしない病院のデータをセットアップ
        ExceptionHour::factory()->create([
            'hospital_id' => Hospital::factory()->create(),
        ]);

        // 実行（Act）
        $response = $this->get(route('hospital.exception-hours.index', ['year' => 2025]));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonFragment(
                [
                    'date'       => '2025-01-01',
                    'start_time' => '09:00',
                    'end_time'   => '12:00',
                    'is_closed'  => false,
                    'reason'     => 'テスト1',
                ],
            )
            ->assertJsonFragment(
                [
                    'date'       => '2025-01-01',
                    'start_time' => '13:00',
                    'end_time'   => '16:00',
                    'is_closed'  => false,
                    'reason'     => 'テスト2',
                ],
            )
            ->assertJsonFragment(
                [
                    'date'       => '2025-01-02',
                    'start_time' => '10:00',
                    'end_time'   => '13:00',
                    'is_closed'  => false,
                    'reason'     => 'テスト3',
                ],
            );
    }

    /**
     * 例外予約受付時間の一覧 0件でもエラーにならないこと
     */
    public function testIndexNoRecordSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.exception-hours.index'));

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
            'date'    => '2025-01-01',
            'periods' => [
                [
                    'start_time' => '09:00',
                    'end_time'   => '12:30',
                    'is_closed'  => false,
                    'reason'     => 'テスト1',
                ],
                [
                    'start_time' => '13:00',
                    'end_time'   => '19:30',
                    'is_closed'  => false,
                    'reason'     => 'テスト2',
                ],
            ],
        ];
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->post(route('hospital.exception-hours.store'), $postData);

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('exception_hours', 2);
    }

    /**
     * 指定した曜日の受付時間の作成/更新 作成と更新されること
     */
    public function testStoreCreateRecordAndUpdateRecordSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->staff, $this->guard);
        ExceptionHour::factory()->create([
            'hospital_id' => $this->hospital->id,
            'date'        => '2025-01-01',
            'start_time'  => '10:00',
            'end_time'    => '13:00',
            'is_closed'   => false,
            'reason'      => 'テスト1',
        ]);
        $postData = [
            'date'    => '2025-01-01',
            'periods' => [
                [
                    'start_time' => '09:00',
                    'end_time'   => '12:30',
                    'is_closed'  => false,
                    'reason'     => 'テスト2',
                ],
                [
                    'start_time' => '13:00',
                    'end_time'   => '19:30',
                    'is_closed'  => false,
                    'reason'     => 'テスト3',
                ],
            ],
        ];

        // 実行（Act）
        $response = $this->post(route('hospital.exception-hours.store'), $postData);

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('exception_hours', 2);

        $records = ExceptionHour::orderBy('id', 'asc')->get();
        $this->assertEquals('2025-01-01', $records[0]->date->format('Y-m-d'));
        $this->assertEquals('09:00', $records[0]->start_time->format('H:i'));
        $this->assertEquals('12:30', $records[0]->end_time->format('H:i'));
        $this->assertFalse($records[0]->is_closed);
        $this->assertEquals('テスト2', $records[0]->reason);

        $this->assertEquals('2025-01-01', $records[1]->date->format('Y-m-d'));
        $this->assertEquals('13:00', $records[1]->start_time->format('H:i'));
        $this->assertEquals('19:30', $records[1]->end_time->format('H:i'));
        $this->assertFalse($records[1]->is_closed);
        $this->assertEquals('テスト3', $records[1]->reason);
    }

    /**
     * 指定した日付の例外受付時間の作成/更新 同じ日に休診日と営業時間は併用するとエラーになること
     */
    public function testStoreEnsureClosedHoursFailure()
    {
        // 準備（Arrange）
        $this->actingAs($this->staff, $this->guard);
        $postData = [
            'date'    => '2025-01-01',
            'periods' => [
                [
                    'start_time' => null,
                    'end_time'   => null,
                    'is_closed'  => true,
                    'reason'     => 'テスト2',
                ],
                [
                    'start_time' => '13:00',
                    'end_time'   => '19:30',
                    'is_closed'  => false,
                    'reason'     => 'テスト3',
                ],
            ],
        ];

        // 実行（Act）
        $response = $this->post(route('hospital.exception-hours.store'), $postData);

        // 検証（Assert）
        $response->assertStatus(422)
            ->assertJson([
                'message' => '同じ日に休診日と営業時間は併用できません。',
            ]);

        $this->assertDatabaseCount('exception_hours', 0);
    }

    /**
     * 指定した日付の例外受付時間の作成/更新 時間が重複して登録されないこと
     */
    public function testStoreNotOverlapFailure()
    {
        // 準備（Arrange）
        $this->actingAs($this->staff, $this->guard);
        $postData = [
            'date'    => '2025-01-01',
            'periods' => [
                [
                    'start_time' => '09:00',
                    'end_time'   => '13:30',
                    'is_closed'  => false,
                    'reason'     => 'テスト2',
                ],
                [
                    'start_time' => '13:00', // 重複している
                    'end_time'   => '19:30',
                    'is_closed'  => false,
                    'reason'     => 'テスト3',
                ],
            ],
        ];

        // 実行（Act）
        $response = $this->post(route('hospital.exception-hours.store'), $postData);

        // 検証（Assert）
        $response->assertStatus(422)
            ->assertJson([
                'message' => '営業時間が重複しています: 09:00-13:30 と 13:00-19:30',
            ]);

        $this->assertDatabaseCount('exception_hours', 0);
    }

    /**
     * 例外予約受付時間の削除 削除できていること
     */
    public function testDestroySuccess()
    {
        // 準備（Arrange）
        $model = ExceptionHour::factory()->create([
            'hospital_id' => $this->hospital->id,
        ]);
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->delete(route('hospital.exception-hours.destroy', ['exception_hour' => $model->id]));

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('exception_hours', 0);
    }

    /**
     * 例外予約受付時間の削除 他の病院の予約受付時間を削除できないこと
     */
    public function testDestroyNotHospitalOwnFailure()
    {
        // 準備（Arrange）
        $model = ExceptionHour::factory()->create([
            'hospital_id' => Hospital::factory()->create(),
        ]);
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->delete(route('hospital.exception-hours.destroy', ['exception_hour' => $model->id]));

        // 検証（Assert）
        $response->assertStatus(404);
    }
}
