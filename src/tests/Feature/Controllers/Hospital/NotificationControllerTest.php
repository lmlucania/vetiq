<?php

declare(strict_types=1);

namespace Feature\Controllers\Hospital;

use App\Models\Hospital;
use App\Models\Notification;
use App\Models\StaffMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;

class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    private $guard = 'staff-members';

    public function setUp():void
    {
        parent::setUp();

        $this->hospital = Hospital::factory()->create();
        $this->staff    = StaffMember::factory()->create([
            'hospital_id' => $this->hospital->id,
        ]);

        $this->notification = Notification::factory()->create([
            'id'           => 1,
            'hospital_id'  => $this->hospital->id,
            'title'        => '健康診断',
            'detail'       => '年に一度の健康診断で、体調をチェックします。',
            'is_published' => true,
            'published_at' => '2025-01-01 00:00',
        ]);

        Notification::factory()->create([
            'id'           => 2,
            'hospital_id'  => $this->hospital->id,
            'title'        => '一般診察',
            'detail'       => '基本的な診察を行います。健康状態のチェックや病気の早期発見をサポートします。',
            'is_published' => true,
            'published_at' => '2025-01-02 00:00',
        ]);

        Notification::factory()->create([
            'id'           => 3,
            'hospital_id'  => $this->hospital->id,
            'title'        => 'ワクチン接種',
            'detail'       => '各種ワクチンを接種し、病気を予防します。',
            'is_published' => true,
            'published_at' => '2025-01-02 00:00',
        ]);

        // 他の病院のお知らせを作成
        $hospital = Hospital::factory()->create();
        StaffMember::factory()->create(['hospital_id' => $hospital->id]);
        $this->otherHospitalNotification = Notification::factory()->create(['hospital_id' => $hospital->id]);
    }

    /**
     * お知らせの一覧 情報が取得できること
     */
    public function testIndexSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.notifications.index'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonFragment([
                'data' => [
                    [
                        'id'           => 1,
                        'title'        => '健康診断',
                        'detail'       => '年に一度の健康診断で、体調をチェックします。',
                        'is_published' => true,
                        'published_at' => '2025-01-01 00:00',
                        ],
                    [
                        'id'           => 2,
                        'title'        => '一般診察',
                        'detail'       => '基本的な診察を行います。健康状態のチェックや病気の早期発見をサポートします。',
                        'is_published' => true,
                        'published_at' => '2025-01-02 00:00',
                        ],
                    [
                        'id'           => 3,
                        'title'        => 'ワクチン接種',
                        'detail'       => '各種ワクチンを接種し、病気を予防します。',
                        'is_published' => true,
                        'published_at' => '2025-01-02 00:00',
                    ],
                ]]);
    }

    /**
     * お知らせの一覧 論理削除済みのデータが取得できないこと
     */
    public function testIndexNotGetDeletedNotificationSuccess()
    {
        // 準備（Arrange）
        Notification::query()->delete();
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.notifications.index'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    /**
     * お知らせの一覧 デフォルトで50件表示されること
     */
    public function testIndexDefaultPerPageSuccess()
    {
        // 準備（Arrange）
        Notification::factory()->count(60)->create(['hospital_id' => $this->hospital->id]);
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.notifications.index'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(50, 'data');
    }

    /**
     * お知らせの一覧 クエリパラメータ指定した表示数と一致していること 10件でテスト
     */
    public function testIndexParamPerPageSuccess()
    {
        // 準備（Arrange）
        Notification::factory()->count(60)->create(['hospital_id' => $this->hospital->id]);
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.notifications.index', ['per_page' => 10]));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(10, 'data');
    }

    /**
     * お知らせの一覧 クエリパラメータ指定したページ数と一致していること 2ページ目でテスト
     */
    public function testIndexParamPageSuccess()
    {
        // 準備（Arrange）
        Notification::query()->forceDelete();
        Notification::factory()->count(59)->create(['hospital_id' => $this->hospital->id]);
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.notifications.index', ['page' => 2]));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(9, 'data')
            ->assertJsonFragment([
                'current_page' => 2,
            ]);
    }

    /**
     * お知らせの一覧 公開時刻の昇順でソート
     */
    public function testIndexSortByPublishedAtAscSuccess()
    {
        // 準備（Arrange）
        Notification::query()->forceDelete();
        Notification::factory()->create(['hospital_id' => $this->hospital->id, 'published_at' => '2000-01-01 00:01']); // レスポンスでは一番目にくる
        Notification::factory()->create(['hospital_id' => $this->hospital->id, 'published_at' => '2000-01-01 00:02']); // レスポンスでは二番目にくる
        Notification::factory()->create(['hospital_id' => $this->hospital->id, 'published_at' => '2000-01-01 00:03']); // レスポンスでは三番目にくる
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.notifications.index', ['sort' => ['published_at']])); // 公開時刻の昇順でソート

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(3, 'data');

        $responseData = $response->json('data');
        $actual       = array_column($responseData, 'published_at');
        $this->assertSame(
            [
                '2000-01-01 00:01',
                '2000-01-01 00:02',
                '2000-01-01 00:03',
                ],
            $actual,
        );
    }

    /**
     * お知らせの一覧 公開時刻の降順でソート
     */
    public function testIndexSortByPublishedAtDescSuccess()
    {
        // 準備（Arrange）
        Notification::query()->forceDelete();
        Notification::factory()->create(['hospital_id' => $this->hospital->id, 'published_at' => '2000-01-01 00:03']); // レスポンスでは一番目にくる
        Notification::factory()->create(['hospital_id' => $this->hospital->id, 'published_at' => '2000-01-01 00:02']); // レスポンスでは二番目にくる
        Notification::factory()->create(['hospital_id' => $this->hospital->id, 'published_at' => '2000-01-01 00:01']); // レスポンスでは三番目にくる
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.notifications.index', ['sort' => ['-published_at']])); // 公開時刻の降順でソート

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(3, 'data');

        $responseData = $response->json('data');
        $actual       = array_column($responseData, 'published_at');
        $this->assertSame(
            [
                '2000-01-01 00:03',
                '2000-01-01 00:02',
                '2000-01-01 00:01',
            ],
            $actual,
        );
    }

    /**
     * お知らせの一覧 複数条件でソート（公開時刻：降順、公開フラグ：昇順）
     */
    public function testIndexMultiSortByPublishedAtDescAndIsPublishedAscSuccess()
    {
        // 準備（Arrange）
        Notification::query()->forceDelete();
        Notification::factory()->create(['hospital_id' => $this->hospital->id, 'published_at' => '2000-01-01 00:03', 'is_published' => true]); // レスポンスでは一番目にくる
        Notification::factory()->create(['hospital_id' => $this->hospital->id, 'published_at' => '2000-01-01 00:03', 'is_published' => true]); // レスポンスでは二番目にくる
        Notification::factory()->create(['hospital_id' => $this->hospital->id, 'published_at' => '2000-01-01 00:01', 'is_published' => false]); // レスポンスでは三番目にくる
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.notifications.index', ['sort' => ['-published_at', 'is_published']])); // 公開時刻：降順、公開フラグ：昇順でソート

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(3, 'data');

        $responseData      = $response->json('data');
        $actualPublishedAt = array_column($responseData, 'published_at');
        $actualIsPublished = array_column($responseData, 'is_published');
        $this->assertSame(
            [
                '2000-01-01 00:03',
                '2000-01-01 00:03',
                '2000-01-01 00:01',
            ],
            $actualPublishedAt,
        );
        $this->assertSame([true, true, false], $actualIsPublished);
    }

    /**
     * お知らせの一覧 keyword検索（名前と説明で部分一致検索）
     */
    public function testIndexSearchKeywordSuccess()
    {
        // 準備（Arrange）
        Notification::query()->forceDelete();
        Notification::factory()->create(['hospital_id' => $this->hospital->id, 'title' => 'テストあ', 'detail' => 'あ']);            // title：前方一致
        Notification::factory()->create(['hospital_id' => $this->hospital->id, 'title' => 'あテスト', 'detail' => 'あ']);            // title：後方一致
        Notification::factory()->create(['hospital_id' => $this->hospital->id, 'title' => 'あテストあ', 'detail' => 'あ']);          // title：部分一致
        Notification::factory()->create(['hospital_id' => $this->hospital->id, 'title' => 'あ', 'detail' => 'テストあ']);            // detail：前方一致
        Notification::factory()->create(['hospital_id' => $this->hospital->id, 'title' => 'あ', 'detail' => 'あテスト']);            // detail：後方一致
        Notification::factory()->create(['hospital_id' => $this->hospital->id, 'title' => 'あ', 'detail' => 'あテストあ']);          // detail：部分一致
        $missingNotification = Notification::factory()->create(['hospital_id' => $this->hospital->id, 'title' => 'あ', 'detail' => 'あ']);  // 一致しない
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.notifications.index', ['keyword' => 'テスト']));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(6, 'data')
            ->assertJsonMissing(['uuid' => $missingNotification->id]);
    }

    /**
     * お知らせの一覧 未ログインユーザーの時に401が返されることをチェック
     */
    public function testIndexWithoutLoginFailure()
    {
        // 準備（Arrange）

        // 実行（Act）
        $response = $this->get(route('hospital.notifications.index'));

        // 検証（Assert）
        $response->assertStatus(401);
    }

    /**
     * お知らせの登録 登録ができること
     */
    public function testStoreSuccess()
    {
        // 準備（Arrange）
        Notification::query()->forceDelete();
        $postData = [
            'title'        => 'テスト名前',
            'detail'       => 'テスト説明',
            'is_published' => true,
            'published_at' => '2025-01-01 01:01',
        ];
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->post(route('hospital.notifications.store'), $postData);

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('notifications', 1);

        $createdNotification = Notification::first();
        $this->assertEquals('テスト名前', $createdNotification->title);
        $this->assertEquals('テスト説明', $createdNotification->detail);
        $this->assertTrue($createdNotification->is_published);
        $this->assertEquals('2025-01-01 01:01', $createdNotification->published_at->format('Y-m-d H:i'));
    }

    /**
     * お知らせの登録 バリデーションエラー（名前が空文字）
     */
    public function testStoreValidationError()
    {
        // 準備（Arrange）
        $postData = [
            'title'        => '', // テスト対象
            'detail'       => 'テスト説明',
            'is_published' => true,
            'published_at' => '2025-01-01 01:01',
        ];
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->post(route('hospital.notifications.store'), $postData);

        // 検証（Assert）
        $response
            ->assertStatus(422)
            ->assertJsonCount(2)
            ->assertJsonFragment([
                'message' => 'バリデーションエラー',
                'errors'  => [
                    'title' => ['validation.required'],
                ],
            ]);
    }

    /**
     * お知らせの詳細 情報が取得できること
     */
    public function testShowSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.notifications.show', ['notification' => $this->notification->id]));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonFragment([
                'id'           => $this->notification->id,
                'title'        => '健康診断',
                'detail'       => '年に一度の健康診断で、体調をチェックします。',
                'is_published' => true,
                'published_at' => '2025-01-01 00:00',
            ]);
    }

    /**
     * お知らせの詳細 他の病院のお知らせが取得できないこと
     */
    public function testShowNotHospitalOwnFailure()
    {
        // 準備（Arrange）
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->get(route('hospital.notifications.show', ['notification' => $this->otherHospitalNotification->id]));

        // 検証（Assert）
        $response->assertStatus(404);
    }

    /**
     * お知らせを更新 病院情報を更新できること
     */
    public function testUpdateSuccess()
    {
        // 準備（Arrange）
        $postData = [
            'title'        => 'テスト名前update',
            'detail'       => 'テスト説明update',
            'is_published' => false,
            'published_at' => '2025-01-01 00:01',
        ];
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->put(route('hospital.notifications.update', ['notification' => $this->notification->id]), $postData);

        // 検証（Assert）
        $response->assertStatus(200);

        $updatedNotification = Notification::find($this->notification->id);
        $this->assertEquals('テスト名前update', $updatedNotification->title);
        $this->assertEquals('テスト説明update', $updatedNotification->detail);
        $this->assertFalse($updatedNotification->is_published);
        $this->assertEquals('2025-01-01 00:01', $updatedNotification->published_at->format('Y-m-d H:i'));
    }

    /**
     * お知らせを更新 他の病院のお知らせを更新できないこと
     */
    public function testUpdateNotHospitalOwnFailure()
    {
        // 準備（Arrange）
        $postData = [
            'title'        => 'テスト名前update',
            'detail'       => 'テスト説明update',
            'is_published' => false,
            'published_at' => '2025-01-01 00:01',
        ];
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->put(route('hospital.notifications.update', ['notification' => $this->otherHospitalNotification->id]), $postData);

        // 検証（Assert）
        $response->assertStatus(404);
    }

    /**
     * お知らせの削除 存在しないuuidを指定した場合に404が返されること
     */
    public function testUpdateNotExistFailure()
    {
        // 準備（Arrange）
        Notification::query()->forceDelete();
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->delete(route('hospital.notifications.destroy', ['notification' => 0]));

        // 検証（Assert）
        $response->assertStatus(404);
    }

    /**
     * お知らせの削除 削除ができること
     */
    public function testDestroySuccess()
    {
        // 準備（Arrange）
        Notification::where('id', '!=', $this->notification->id)->forceDelete();
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->delete(route('hospital.notifications.destroy', ['notification' => $this->notification->id]));

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('notifications', 0);
    }

    /**
     * お知らせの削除 他の病院のお知らせを削除できないこと
     */
    public function testDestroyNotHospitalOwnFailure()
    {
        // 準備（Arrange）
        Notification::where('id', '!=', $this->otherHospitalNotification->id)->forceDelete();
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->delete(route('hospital.notifications.destroy', ['notification' => $this->otherHospitalNotification->id]));

        // 検証（Assert）
        $response->assertStatus(404);

        $this->assertDatabaseCount('notifications', 1);
    }

    /**
     * お知らせの削除 存在しないuuidを指定した場合に404が返されること
     */
    public function testDestroyNotExistFailure()
    {
        // 準備（Arrange）
        Notification::query()->forceDelete();
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->delete(route('hospital.notifications.destroy', ['notification' => 0]));

        // 検証（Assert）
        $response->assertStatus(404);
    }
}
