<?php

namespace Feature\Controllers\User;

use App\Domains\Pet\Enum\Gender;
use App\Models\Hospital;
use App\Models\HospitalViewHistory;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HospitalViewHistoryControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $guard = 'users';

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * 病院の閲覧履歴の一覧 一覧が取得できること
     */
    public function testIndexSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        HospitalViewHistory::factory()->create([
            'hospital_id' => Hospital::factory()->create()->id,
            'user_id' => $this->user->id,
            'updated_at' => '2025-01-01 01:02:03'
        ]);
        HospitalViewHistory::factory()->create([ // 取得されない
            'hospital_id' => Hospital::factory()->create()->id,
            'user_id' => User::factory()->create()->id,
        ]);


        // 実行（Act）
        $response = $this->get(route('user.hospital-view-histories.index'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'updated_at' => '2025-01-01 01:02'
            ]);
    }

    /**
     * 病院の閲覧履歴を削除 削除ができること
     */
    public function testDestroySuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        $hospital = Hospital::factory()->create();
        HospitalViewHistory::factory()->create([
            'hospital_id' => $hospital->id,
            'user_id' => $this->user->id,
        ]);


        // 実行（Act）
        $response = $this->delete(
            route('user.hospital-view-histories.destroy', ['hospital_view_history' => $hospital->id])
        );

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('hospital_view_histories', 0);
    }
}
