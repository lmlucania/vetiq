<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Hospital;

use App\Models\Hospital;
use App\Models\StaffMember;
use App\Models\Vet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;

class VetControllerTest extends TestCase
{
    use RefreshDatabase;

    private $guard = 'staff-members';

    public function setUp():void
    {
        parent::setUp();

        // ログインする病院のデータをセットアップ
        $this->hospital = Hospital::factory()->create(['id' => 1]);
        $this->staff    = StaffMember::factory()->create([
            'id'          => 1,
            'hospital_id' => $this->hospital->id,
            ]);

        $this->vet = Vet::factory()->create([
            'id'                 => 1,
            'uuid'               => '126f1b66-26d0-43b5-8160-1ce09ad3f683',
            'hospital_id'        => $this->hospital->id,
            'last_name'          => 'テスト姓1',
            'first_name'         => 'テスト名1',
            'accept_appointment' => true,
            'remark'             => 'テスト備考1',

        ]);
        Vet::factory()->create([
            'id'                 => 2,
            'uuid'               => 'ecf0cc16-5700-403b-9551-3a739d5949ea',
            'hospital_id'        => $this->hospital->id,
            'last_name'          => 'テスト姓2',
            'first_name'         => 'テスト名2',
            'accept_appointment' => false,
            'remark'             => 'テスト備考2',
        ]);
        Vet::factory()->create([
            'id'                 => 3,
            'uuid'               => '3667d80e-9f20-46f4-854b-8efa648c71c0',
            'hospital_id'        => $this->hospital->id,
            'last_name'          => 'テスト姓3',
            'first_name'         => 'テスト名3',
            'accept_appointment' => true,
            'remark'             => 'テスト備考3',
        ]);

        // ログインしない病院のデータをセットアップ
        $nonLoginHospital = Hospital::factory()->create();
        StaffMember::factory()->create([
            'hospital_id' => $nonLoginHospital->id,
        ]);
        $this->nonLoginHospitalVet = Vet::factory()->create([
            'id'          => 4,
            'hospital_id' => $nonLoginHospital->id,
        ]);
    }

    /**
     * 獣医師の一覧 情報が取得できること
     */
    public function testIndexSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.vets.index'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonFragment([
                'data' => [
                    [
                        'id'                 => 1,
                        'uuid'               => '126f1b66-26d0-43b5-8160-1ce09ad3f683',
                        'last_name'          => 'テスト姓1',
                        'first_name'         => 'テスト名1',
                        'accept_appointment' => true,
                        'remark'             => 'テスト備考1',
                    ],
                    [
                        'id'                 => 2,
                        'uuid'               => 'ecf0cc16-5700-403b-9551-3a739d5949ea',
                        'last_name'          => 'テスト姓2',
                        'first_name'         => 'テスト名2',
                        'accept_appointment' => false,
                        'remark'             => 'テスト備考2',
                    ],
                    [
                        'id'                 => 3,
                        'uuid'               => '3667d80e-9f20-46f4-854b-8efa648c71c0',
                        'last_name'          => 'テスト姓3',
                        'first_name'         => 'テスト名3',
                        'accept_appointment' => true,
                        'remark'             => 'テスト備考3',
                    ],
                ]]);
    }

    /**
     * 獣医師の一覧 論理削除済みのデータが取得できないこと
     */
    public function testIndexNotGetDeletedMenuSuccess()
    {
        // 準備（Arrange）
        Vet::query()->delete();
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.vets.index'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    /**
     * 獣医師の一覧 デフォルトで50件表示されること
     */
    public function testIndexDefaultPerPageSuccess()
    {
        // 準備（Arrange）
        Vet::factory()->count(60)->create(['hospital_id' => $this->hospital->id]);
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.vets.index'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(50, 'data');
    }

    /**
     * 獣医師の一覧 keyword検索（名前（姓）と名前（名）で部分一致検索）
     */
    public function testIndexSearchKeywordSuccess()
    {
        // 準備（Arrange）
        Vet::query()->forceDelete();
        Vet::factory()->create(['hospital_id' => $this->hospital->id, 'last_name' => 'テストあ', 'first_name' => 'あ']);            // last_name：前方一致
        Vet::factory()->create(['hospital_id' => $this->hospital->id, 'last_name' => 'あテスト', 'first_name' => 'あ']);            // last_name：後方一致
        Vet::factory()->create(['hospital_id' => $this->hospital->id, 'last_name' => 'あテストあ', 'first_name' => 'あ']);          // last_name：部分一致
        Vet::factory()->create(['hospital_id' => $this->hospital->id, 'last_name' => 'あ', 'first_name' => 'テストあ']);            // first_name：前方一致
        Vet::factory()->create(['hospital_id' => $this->hospital->id, 'last_name' => 'あ', 'first_name' => 'あテスト']);            // first_name：後方一致
        Vet::factory()->create(['hospital_id' => $this->hospital->id, 'last_name' => 'あ', 'first_name' => 'あテストあ']);          // first_name：部分一致
        $missingVet = Vet::factory()->create(['hospital_id' => $this->hospital->id, 'last_name' => 'あ', 'first_name' => 'あ']);  // 一致しない
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.vets.index', ['keyword' => 'テスト']));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(6, 'data')
            ->assertJsonMissing(['uuid' => $missingVet->id]);
    }

    /**
     * 獣医師の一覧 未ログインユーザーの時に401が返されることをチェック
     */
    public function testIndexWithoutLoginFailure()
    {
        // 準備（Arrange）

        // 実行（Act）
        $response = $this->get(route('hospital.vets.index'));

        // 検証（Assert）
        $response->assertStatus(401);
    }

    /**
     * 獣医師の登録 登録ができること
     */
    public function testStoreSuccess()
    {
        // 準備（Arrange）
        Vet::query()->forceDelete();
        $postData = [
            'last_name'          => 'テスト姓',
            'first_name'         => 'テスト名',
            'accept_appointment' => true,
            'remark'             => 'テスト備考',
        ];
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->post(route('hospital.vets.store'), $postData);

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('vets', 1);

        $newRecord = Vet::first();
        $this->assertEquals('テスト姓', $newRecord->last_name);
        $this->assertEquals('テスト名', $newRecord->first_name);
        $this->assertTrue($newRecord->accept_appointment);
        $this->assertEquals('テスト備考', $newRecord->remark);
    }

    /**
     * 獣医師の登録 バリデーションエラー
     */
    public function testStoreValidationError()
    {
        // 準備（Arrange）
        $postData = [
            'last_name'          => '', // テスト対象
            'first_name'         => '', // テスト対象
            'accept_appointment' => '', // テスト対象
            'remark'             => '', // テスト対象
        ];
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->post(route('hospital.vets.store'), $postData);

        // 検証（Assert）
        $response
            ->assertStatus(422)
            ->assertJsonCount(2)
            ->assertJsonFragment([
                'message' => 'バリデーションエラー',
                'errors'  => [
                    'last_name'          => ['validation.required'],
                    'first_name'         => ['validation.required'],
                    'accept_appointment' => ['validation.required'],
                    'remark'             => ['validation.required'],
                ],
            ]);
    }

    /**
     * 獣医師の詳細 情報が取得できること
     */
    public function testShowSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.vets.show', ['vet' => $this->vet->id]));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(6, 'data')
            ->assertJsonFragment([
                'id'                 => 1,
                'uuid'               => '126f1b66-26d0-43b5-8160-1ce09ad3f683',
                'last_name'          => 'テスト姓1',
                'first_name'         => 'テスト名1',
                'accept_appointment' => true,
                'remark'             => 'テスト備考1',
            ]);
    }

    /**
     * 獣医師の詳細 他の病院の獣医師が取得できないこと
     */
    public function testShowNotHospitalOwnFailure()
    {
        // 準備（Arrange）
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.vets.show', ['vet' => $this->nonLoginHospitalVet->id]));

        // 検証（Assert）
        $response->assertStatus(404);
    }

    /**
     * 獣医師の更新 更新ができること
     */
    public function testUpdateSuccess()
    {
        // 準備（Arrange）
        $postData = [
            'last_name'          => 'テスト姓',
            'first_name'         => 'テスト名',
            'accept_appointment' => false,
            'remark'             => 'テスト備考',
        ];
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->put(route('hospital.vets.update', ['vet' => $this->vet->id]), $postData);

        // 検証（Assert）
        $response->assertStatus(200);

        $updatedRecord = Vet::find($this->vet->id);
        $this->assertEquals('テスト姓', $updatedRecord->last_name);
        $this->assertEquals('テスト名', $updatedRecord->first_name);
        $this->assertFalse($updatedRecord->accept_appointment);
        $this->assertEquals('テスト備考', $updatedRecord->remark);
    }

    /**
     * 獣医師の更新 他の病院の獣医師を更新できないこと
     */
    public function testUpdateNotHospitalOwnFailure()
    {
        // 準備（Arrange）
        $postData = [
            'last_name'          => 'テスト姓',
            'first_name'         => 'テスト名',
            'accept_appointment' => false,
            'remark'             => 'テスト備考',
        ];
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->put(route('hospital.vets.update', ['vet' => $this->nonLoginHospitalVet->id]), $postData);

        // 検証（Assert）
        $response->assertStatus(404);
    }

    /**
     * 獣医師の更新 存在しないuuidを指定した場合に404が返されること
     */
    public function testUpdateNotExistFailure()
    {
        // 準備（Arrange）
        Vet::query()->forceDelete();
        $postData = [
            'last_name'          => 'テスト姓',
            'first_name'         => 'テスト名',
            'accept_appointment' => false,
            'remark'             => 'テスト備考',
        ];
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->put(route('hospital.vets.update', ['vet' => 0]), $postData);

        // 検証（Assert）
        $response->assertStatus(404);
    }

    /**
     * 獣医師の削除 論理削除ができること
     */
    public function testDestroySuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->delete(route('hospital.vets.destroy', ['vet' => $this->vet->id]));

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('vets', 4);

        $this->assertSoftDeleted(
            table: 'vets',
            data: [
                'id' => $this->vet->id,
            ],
        );
    }

    /**
     * 獣医師の削除 他の病院の獣医を削除できないこと
     */
    public function testDestroyNotHospitalOwnFailure()
    {
        // 準備（Arrange）
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->delete(route('hospital.vets.destroy', ['vet' => $this->nonLoginHospitalVet->id]));

        // 検証（Assert）
        $response->assertStatus(404);
    }

    /**
     * 獣医師の削除 病院に所属する獣医師が1名の時に削除できないこと
     */
    public function testDestroyHospitalHasOneVetFailure()
    {
        // 準備（Arrange）
        Vet::where('id', '!=', $this->vet->id)->forceDelete();
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->delete(route('hospital.vets.destroy', ['vet' => $this->vet->id]));

        // 検証（Assert）
        $response
            ->assertStatus(422)
            ->assertJsonCount(2)
            ->assertJsonFragment([
                'error'   => 'Unprocessable Entity',
                'message' => '獣医が1人しかいないため削除できません。',
            ]);
        ;

        $this->assertDatabaseCount('vets', 1);
    }

    /**
     * 獣医師の削除 存在しないuuidを指定した場合に404が返されること
     */
    public function testDestroyNotExistFailure()
    {
        // 準備（Arrange）
        Vet::query()->forceDelete();
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->delete(route('hospital.vets.destroy', ['vet' => 0]));

        // 検証（Assert）
        $response->assertStatus(404);
    }
}
