<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Hospital;

use App\Models\Hospital;
use App\Models\StaffModel;
use App\Models\VetModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;

class VetControllerTest extends TestCase
{
    use RefreshDatabase;

    private $guard = 'staffs';

    public function setUp():void
    {
        parent::setUp();

        // ログインする病院のデータをセットアップ
        $this->hospital = Hospital::factory()->create(['id' => 1]);
        $this->staff    = StaffModel::factory()->create([
            'id'          => 1,
            'hospital_id' => $this->hospital->id,
            ]);

        $this->vet = VetModel::factory()->create([
            'id'                 => 1,
            'uuid'               => '126f1b66-26d0-43b5-8160-1ce09ad3f683',
            'hospital_id'        => $this->hospital->id,
            'last_name'          => 'テスト姓1',
            'first_name'         => 'テスト名1',
            'accept_appointment' => true,
            'remark'             => 'テスト備考1',

        ]);
        VetModel::factory()->create([
            'id'                 => 2,
            'uuid'               => 'ecf0cc16-5700-403b-9551-3a739d5949ea',
            'hospital_id'        => $this->hospital->id,
            'last_name'          => 'テスト姓2',
            'first_name'         => 'テスト名2',
            'accept_appointment' => false,
            'remark'             => 'テスト備考2',
        ]);
        VetModel::factory()->create([
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
        StaffModel::factory()->create([
            'hospital_id' => $nonLoginHospital->id,
        ]);
        $this->nonLoginHospitalVet = VetModel::factory()->create([
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
                        'uuid'               => '126f1b66-26d0-43b5-8160-1ce09ad3f683',
                        'last_name'          => 'テスト姓1',
                        'first_name'         => 'テスト名1',
                        'accept_appointment' => true,
                        'remark'             => 'テスト備考1',
                    ],
                    [
                        'uuid'               => 'ecf0cc16-5700-403b-9551-3a739d5949ea',
                        'last_name'          => 'テスト姓2',
                        'first_name'         => 'テスト名2',
                        'accept_appointment' => false,
                        'remark'             => 'テスト備考2',
                    ],
                    [
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
        VetModel::query()->delete();
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
        VetModel::factory()->count(60)->create(['hospital_id' => $this->hospital->id]);
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
        VetModel::query()->forceDelete();
        VetModel::factory()->create(['hospital_id' => $this->hospital->id, 'last_name' => 'テストあ', 'first_name' => 'あ']);            // last_name：前方一致
        VetModel::factory()->create(['hospital_id' => $this->hospital->id, 'last_name' => 'あテスト', 'first_name' => 'あ']);            // last_name：後方一致
        VetModel::factory()->create(['hospital_id' => $this->hospital->id, 'last_name' => 'あテストあ', 'first_name' => 'あ']);          // last_name：部分一致
        VetModel::factory()->create(['hospital_id' => $this->hospital->id, 'last_name' => 'あ', 'first_name' => 'テストあ']);            // first_name：前方一致
        VetModel::factory()->create(['hospital_id' => $this->hospital->id, 'last_name' => 'あ', 'first_name' => 'あテスト']);            // first_name：後方一致
        VetModel::factory()->create(['hospital_id' => $this->hospital->id, 'last_name' => 'あ', 'first_name' => 'あテストあ']);          // first_name：部分一致
        $missingVet = VetModel::factory()->create(['hospital_id' => $this->hospital->id, 'last_name' => 'あ', 'first_name' => 'あ']);  // 一致しない
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.vets.index', ['keyword' => 'テスト']));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(6, 'data')
            ->assertJsonMissing(['uuid' => $missingVet->uuid]);
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
        VetModel::query()->forceDelete();
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

        $newRecord = VetModel::first();
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
        $response = $this->get(route('hospital.vets.show', ['vet' => $this->vet->uuid]));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonFragment([
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
        $response = $this->get(route('hospital.vets.show', ['vet' => $this->nonLoginHospitalVet->uuid]));

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
        $response = $this->put(route('hospital.vets.update', ['vet' => $this->vet->uuid]), $postData);

        // 検証（Assert）
        $response->assertStatus(200);

        $updatedRecord = VetModel::find($this->vet->id);
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
        $response = $this->put(route('hospital.vets.update', ['vet' => $this->nonLoginHospitalVet->uuid]), $postData);

        // 検証（Assert）
        $response->assertStatus(404);
    }

    /**
     * 獣医師の更新 存在しないuuidを指定した場合に404が返されること
     */
    public function testUpdateNotExistFailure()
    {
        // 準備（Arrange）
        VetModel::query()->forceDelete();
        $postData = [
            'last_name'          => 'テスト姓',
            'first_name'         => 'テスト名',
            'accept_appointment' => false,
            'remark'             => 'テスト備考',
        ];
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->put(route('hospital.vets.update', ['vet' => '1667cff9-71e5-4719-953c-e074507d2d3d']), $postData);

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
        $response = $this->delete(route('hospital.vets.destroy', ['vet' => $this->vet->uuid]));

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
        $response = $this->delete(route('hospital.vets.destroy', ['vet' => $this->nonLoginHospitalVet->uuid]));

        // 検証（Assert）
        $response->assertStatus(404);
    }

    /**
     * 獣医師の削除 病院に所属する獣医師が1名の時に削除できないこと
     */
    public function testDestroyHospitalHasOneVetFailure()
    {
        // 準備（Arrange）
        VetModel::where('id', '!=', $this->vet->id)->forceDelete();
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->delete(route('hospital.vets.destroy', ['vet' => $this->vet->uuid]));

        // 検証（Assert）
        $response
            ->assertStatus(422)
            ->assertJsonCount(2)
            ->assertJsonFragment([
                'error'   => 'Unprocessable Entity',
                'message' => 'この病院には1人の獣医師しかいないため、削除できません。',
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
        VetModel::query()->forceDelete();
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->delete(route('hospital.vets.destroy', ['vet' => '1667cff9-71e5-4719-953c-e074507d2d3d']));

        // 検証（Assert）
        $response->assertStatus(404);
    }
}
