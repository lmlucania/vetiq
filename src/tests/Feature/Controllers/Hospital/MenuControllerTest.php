<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Hospital;

use App\Models\HospitalModel;
use App\Models\MenuModel;
use App\Models\StaffModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

class MenuControllerTest extends TestCase
{
    use RefreshDatabase;

    private $guard = 'staffs';

    public function setUp():void
    {
        parent::setUp();

        $this->hospital = HospitalModel::factory()->create([
            'uuid'         => 'b90612f5-3446-47d7-b66a-12ff54963050',
            'name'         => '裕美子病院',
            'zipcode'      => '1234567',
            'address'      => '岩手県佐藤市東区吉本町佐々木1-2-3',
            'phone'        => '0123456789',
            'is_published' => true,
        ]);

        $this->staff = StaffModel::factory()->create([
            'hospital_id' => $this->hospital->id,
            'name'        => '山岸太一',
            'email'       => 'staff+1@example.com',
            'password'    => Hash::make('password'),
        ]);

        $this->menu = MenuModel::factory()->create([
            'id'            => 1,
            'uuid'          => '126f1b66-26d0-43b5-8160-1ce09ad3f683',
            'hospital_id'   => $this->hospital->id,
            'name'          => '健康診断',
            'detail'        => '年に一度の健康診断で、体調をチェックします。',
            'required_time' => 30,
            'is_published'  => true,
        ]);

        MenuModel::factory()->create([
            'uuid'          => 'ecf0cc16-5700-403b-9551-3a739d5949ea',
            'hospital_id'   => $this->hospital->id,
            'name'          => '一般診察',
            'detail'        => '基本的な診察を行います。健康状態のチェックや病気の早期発見をサポートします。',
            'required_time' => 60,
            'is_published'  => true,
        ]);

        MenuModel::factory()->create([
            'uuid'          => '3667d80e-9f20-46f4-854b-8efa648c71c0',
            'hospital_id'   => $this->hospital->id,
            'name'          => 'ワクチン接種',
            'detail'        => '各種ワクチンを接種し、病気を予防します。',
            'required_time' => 90,
            'is_published'  => true,
        ]);

        // 他の病院の診察メニューを作成
        $hospital = HospitalModel::factory()->create();
        StaffModel::factory()->create(['hospital_id' => $hospital->id]);
        $this->otherHospitalMenu = MenuModel::factory()->create(['hospital_id' => $hospital->id]);
    }

    /**
     * 診察メニューの一覧 情報が取得できること
     */
    public function testIndexSuccess()
    {
        // 準備（Arrange）
        Sanctum::actingAs($this->staff, ['*'], $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.menus.index'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonFragment([
                'data' => [
                    [
                        'uuid'          => '126f1b66-26d0-43b5-8160-1ce09ad3f683',
                        'name'          => '健康診断',
                        'detail'        => '年に一度の健康診断で、体調をチェックします。',
                        'required_time' => 30,
                        'is_published'  => true,
                        ],
                    [
                        'uuid'          => 'ecf0cc16-5700-403b-9551-3a739d5949ea',
                        'name'          => '一般診察',
                        'detail'        => '基本的な診察を行います。健康状態のチェックや病気の早期発見をサポートします。',
                        'required_time' => 60,
                        'is_published'  => true,
                        ],
                    [
                        'uuid'          => '3667d80e-9f20-46f4-854b-8efa648c71c0',
                        'name'          => 'ワクチン接種',
                        'detail'        => '各種ワクチンを接種し、病気を予防します。',
                        'required_time' => 90,
                        'is_published'  => true,
                    ],
                ]]);
    }

    /**
     * 診察メニューの一覧 論理削除済みのデータが取得できないこと
     */
    public function testIndexNotGetDeletedMenuSuccess()
    {
        // 準備（Arrange）
        MenuModel::query()->delete();
        Sanctum::actingAs($this->staff, ['*'], $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.menus.index'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    /**
     * 診察メニューの一覧 デフォルトで50件表示されること
     */
    public function testIndexDefaultPerPageSuccess()
    {
        // 準備（Arrange）
        MenuModel::factory()->count(60)->create(['hospital_id' => $this->hospital->id]);
        Sanctum::actingAs($this->staff, ['*'], $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.menus.index'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(50, 'data');
    }

    /**
     * 診察メニューの一覧 クエリパラメータ指定した表示数と一致していること 10件でテスト
     */
    public function testIndexParamPerPageSuccess()
    {
        // 準備（Arrange）
        MenuModel::factory()->count(60)->create(['hospital_id' => $this->hospital->id]);
        Sanctum::actingAs($this->staff, ['*'], $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.menus.index', ['per_page' => 10]));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(10, 'data');
    }

    /**
     * 診察メニューの一覧 クエリパラメータ指定したページ数と一致していること 2ページ目でテスト
     */
    public function testIndexParamPageSuccess()
    {
        // 準備（Arrange）
        MenuModel::query()->forceDelete();
        MenuModel::factory()->count(59)->create(['hospital_id' => $this->hospital->id]);
        Sanctum::actingAs($this->staff, ['*'], $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.menus.index', ['page' => 2]));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(9, 'data')
            ->assertJsonFragment([
                'current_page' => 2,
            ]);
    }

    /**
     * 診察メニューの一覧 名前の昇順でソート
     */
    public function testIndexSortByNameAscSuccess()
    {
        // 準備（Arrange）
        MenuModel::query()->forceDelete();
        MenuModel::factory()->create(['hospital_id' => $this->hospital->id, 'name' => 'c']); // レスポンスでは三番目にくる
        MenuModel::factory()->create(['hospital_id' => $this->hospital->id, 'name' => 'b']); // レスポンスでは二番目にくる
        MenuModel::factory()->create(['hospital_id' => $this->hospital->id, 'name' => 'a']); // レスポンスでは一番目にくる
        Sanctum::actingAs($this->staff, ['*'], $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.menus.index', ['sort' => ['name']])); // 名前の昇順でソート

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(3, 'data');

        $responseData = $response->json('data');
        $actualNames  = array_column($responseData, 'name');
        $this->assertSame(['a', 'b', 'c'], $actualNames);
    }

    /**
     * 診察メニューの一覧 名前の降順でソート
     */
    public function testIndexSortByNameDescSuccess()
    {
        // 準備（Arrange）
        MenuModel::query()->forceDelete();
        MenuModel::factory()->create(['hospital_id' => $this->hospital->id, 'name' => 'a']); // レスポンスでは三番目にくる
        MenuModel::factory()->create(['hospital_id' => $this->hospital->id, 'name' => 'b']); // レスポンスでは二番目にくる
        MenuModel::factory()->create(['hospital_id' => $this->hospital->id, 'name' => 'c']); // レスポンスでは一番目にくる
        Sanctum::actingAs($this->staff, ['*'], $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.menus.index', ['sort' => ['-name']])); // 名前の降順でソート

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(3, 'data');

        $responseData = $response->json('data');
        $actualNames  = array_column($responseData, 'name');
        $this->assertSame(['c', 'b', 'a'], $actualNames);
    }

    /**
     * 診察メニューの一覧 複数条件でソート（名前：降順、説明：昇順）
     */
    public function testIndexMultiSortByNameDescAndDetailAscSuccess()
    {
        // 準備（Arrange）
        MenuModel::query()->forceDelete();
        MenuModel::factory()->create(['hospital_id' => $this->hospital->id, 'name' => 'a', 'detail' => 'aa']); // レスポンスでは二番目にくる
        MenuModel::factory()->create(['hospital_id' => $this->hospital->id, 'name' => 'a', 'detail' => 'bb']); // レスポンスでは三番目にくる
        MenuModel::factory()->create(['hospital_id' => $this->hospital->id, 'name' => 'c', 'detail' => 'cc']); // レスポンスでは一番目にくる
        Sanctum::actingAs($this->staff, ['*'], $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.menus.index', ['sort' => ['-name', 'detail']])); // 名前：降順、説明：昇順でソート

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(3, 'data');

        $responseData  = $response->json('data');
        $actualNames   = array_column($responseData, 'name');
        $actualDetails = array_column($responseData, 'detail');
        $this->assertSame(['c', 'a', 'a'], $actualNames);
        $this->assertSame(['cc', 'aa', 'bb'], $actualDetails);
    }

    /**
     * 診察メニューの一覧 keyword検索（名前と説明で部分一致検索）
     */
    public function testIndexSearchKeywordSuccess()
    {
        // 準備（Arrange）
        MenuModel::query()->forceDelete();
        MenuModel::factory()->create(['hospital_id' => $this->hospital->id, 'name' => 'テストあ', 'detail' => 'あ']);            // name：前方一致
        MenuModel::factory()->create(['hospital_id' => $this->hospital->id, 'name' => 'あテスト', 'detail' => 'あ']);            // name：後方一致
        MenuModel::factory()->create(['hospital_id' => $this->hospital->id, 'name' => 'あテストあ', 'detail' => 'あ']);          // name：部分一致
        MenuModel::factory()->create(['hospital_id' => $this->hospital->id, 'name' => 'あ', 'detail' => 'テストあ']);            // detail：前方一致
        MenuModel::factory()->create(['hospital_id' => $this->hospital->id, 'name' => 'あ', 'detail' => 'あテスト']);            // detail：後方一致
        MenuModel::factory()->create(['hospital_id' => $this->hospital->id, 'name' => 'あ', 'detail' => 'あテストあ']);          // detail：部分一致
        $missingMenu = MenuModel::factory()->create(['hospital_id' => $this->hospital->id, 'name' => 'あ', 'detail' => 'あ']);  // 一致しない
        Sanctum::actingAs($this->staff, ['*'], $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.menus.index', ['keyword' => 'テスト']));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(6, 'data')
            ->assertJsonMissing(['uuid' => $missingMenu->uuid]);
    }

    /**
     * 診察メニューの一覧 未ログインユーザーの時に401が返されることをチェック
     */
    public function testIndexWithoutLoginFailure()
    {
        // 準備（Arrange）

        // 実行（Act）
        $response = $this->get(route('hospital.menus.index'));

        // 検証（Assert）
        $response->assertStatus(401);
    }

    /**
     * 診察メニューの登録 登録ができること
     */
    public function testStoreSuccess()
    {
        // 準備（Arrange）
        MenuModel::query()->forceDelete();
        $postData = [
            'name'          => 'テスト名前',
            'detail'        => 'テスト説明',
            'required_time' => 30,
            'is_published'  => true,
        ];
        Sanctum::actingAs($this->staff, ['*'], $this->guard);

        // 実行（Act）
        $response = $this->post(route('hospital.menus.store'), $postData);

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('menus', 1);

        $createdMenu = MenuModel::first();
        $this->assertEquals('テスト名前', $createdMenu->name);
        $this->assertEquals('テスト説明', $createdMenu->detail);
        $this->assertEquals(30, $createdMenu->required_time);
        $this->assertTrue($createdMenu->is_published);
    }

    /**
     * 診察メニューの登録 バリデーションエラー（名前が空文字）
     */
    public function testStoreValidationError()
    {
        // 準備（Arrange）
        $postData = [
            'name'          => '', // テスト対象
            'detail'        => 'テスト説明',
            'required_time' => 30,
            'is_published'  => true,
        ];
        Sanctum::actingAs($this->staff, ['*'], $this->guard);

        // 実行（Act）
        $response = $this->post(route('hospital.menus.store'), $postData);

        // 検証（Assert）
        $response
            ->assertStatus(422)
            ->assertJsonCount(2)
            ->assertJsonFragment([
                'message' => 'バリデーションエラー',
                'errors'  => [
                    'name' => ['validation.required'],
                ],
            ]);
    }

    /**
     * 診察メニューの詳細 情報が取得できること
     */
    public function testShowSuccess()
    {
        // 準備（Arrange）
        Sanctum::actingAs($this->staff, ['*'], $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.menus.show', ['menu' => $this->menu->uuid]));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonFragment([
                'uuid'          => '126f1b66-26d0-43b5-8160-1ce09ad3f683',
                'name'          => '健康診断',
                'detail'        => '年に一度の健康診断で、体調をチェックします。',
                'required_time' => 30,
                'is_published'  => true,
            ]);
    }

    /**
     * 診察メニューの詳細 他の病院の診察メニューが取得できないこと
     */
    public function testShowNotHospitalOwnFailure()
    {
        // 準備（Arrange）
        Sanctum::actingAs($this->staff, ['*'], $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.menus.show', ['menu' => $this->otherHospitalMenu->uuid]));

        // 検証（Assert）
        $response->assertStatus(404);
    }

    /**
     * 診察メニューを更新 病院情報を更新できること
     */
    public function testUpdateSuccess()
    {
        // 準備（Arrange）
        $postData = [
            'name'          => 'テスト名前update',
            'detail'        => 'テスト説明update',
            'required_time' => 100,
            'is_published'  => false,
        ];
        Sanctum::actingAs($this->staff, ['*'], $this->guard);

        // 実行（Act）
        $response = $this->put(route('hospital.menus.update', ['menu' => $this->menu->uuid]), $postData);

        // 検証（Assert）
        $response->assertStatus(200);

        $updatedMenu = MenuModel::find($this->menu->id);
        $this->assertEquals('テスト名前update', $updatedMenu->name);
        $this->assertEquals('テスト説明update', $updatedMenu->detail);
        $this->assertEquals(100, $updatedMenu->required_time);
        $this->assertFalse($updatedMenu->is_published);
    }

    /**
     * 診察メニューを更新 他の病院の診察メニューを更新できないこと
     */
    public function testUpdateNotHospitalOwnFailure()
    {
        // 準備（Arrange）
        $postData = [
            'name'          => 'テスト名前update',
            'detail'        => 'テスト説明update',
            'required_time' => 100,
            'is_published'  => false,
        ];
        Sanctum::actingAs($this->staff, ['*'], $this->guard);

        // 実行（Act）
        $response = $this->put(route('hospital.menus.update', ['menu' => $this->otherHospitalMenu->uuid]), $postData);

        // 検証（Assert）
        $response->assertStatus(404);
    }

    /**
     * 診察メニューの削除 存在しないuuidを指定した場合に404が返されること
     */
    public function testUpdateNotExistFailure()
    {
        // 準備（Arrange）
        MenuModel::query()->forceDelete();
        Sanctum::actingAs($this->staff, ['*'], $this->guard);

        // 実行（Act）
        $response = $this->delete(route('hospital.menus.destroy', ['menu' => '1667cff9-71e5-4719-953c-e074507d2d3d']));

        // 検証（Assert）
        $response->assertStatus(404);
    }

    /**
     * 診察メニューの削除 論理削除ができること
     */
    public function testDestroySuccess()
    {
        // 準備（Arrange）
        MenuModel::where('id', '!=', $this->menu->id)->forceDelete();
        Sanctum::actingAs($this->staff, ['*'], $this->guard);

        // 実行（Act）
        $response = $this->delete(route('hospital.menus.destroy', ['menu' => $this->menu->uuid]));

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('menus', 1);

        $this->assertSoftDeleted(
            table: 'menus',
            data: [
                'id' => 1,
            ],
        );
    }

    /**
     * 診察メニューの削除 他の病院の診察メニューを削除できないこと
     */
    public function testDestroyNotHospitalOwnFailure()
    {
        // 準備（Arrange）
        MenuModel::where('id', '!=', $this->otherHospitalMenu->id)->forceDelete();
        Sanctum::actingAs($this->staff, ['*'], $this->guard);

        // 実行（Act）
        $response = $this->delete(route('hospital.menus.destroy', ['menu' => $this->otherHospitalMenu->uuid]));

        // 検証（Assert）
        $response->assertStatus(404);

        $this->assertDatabaseCount('menus', 1);
    }

    /**
     * 診察メニューの削除 存在しないuuidを指定した場合に404が返されること
     */
    public function testDestroyNotExistFailure()
    {
        // 準備（Arrange）
        MenuModel::query()->forceDelete();
        Sanctum::actingAs($this->staff, ['*'], $this->guard);

        // 実行（Act）
        $response = $this->delete(route('hospital.menus.destroy', ['menu' => '1667cff9-71e5-4719-953c-e074507d2d3d']));

        // 検証（Assert）
        $response->assertStatus(404);
    }
}
