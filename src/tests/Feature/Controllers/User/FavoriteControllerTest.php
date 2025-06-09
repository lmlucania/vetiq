<?php

declare(strict_types=1);

namespace Feature\Controllers\User;

use App\Models\Favorite;
use App\Models\Hospital;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $guard = 'users';

    protected function setUp(): void
    {
        parent::setUp();

        $this->user     = User::factory()->create();
        $this->hospital = Hospital::factory()->create();
    }

    /**
     * お気に入り一覧 ユーザーのお気に入り一覧が取得できること
     */
    public function testIndexSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        $favorite = Favorite::factory()->create([
            'user_id'     => $this->user->id,
            'hospital_id' => $this->hospital->id,
        ]);
        Favorite::factory()->create(); // 他ユーザーのお気に入り

        // 実行（Act）
        $response = $this->get(route('user.favorites.index'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
            'data' => [
                [
                    'id'       => $favorite->id,
                    'hospital' => [
                        'id'           => $this->hospital->id,
                        'uuid'         => $this->hospital->uuid,
                        'name'         => $this->hospital->name,
                        'zipcode'      => $this->hospital->zipcode,
                        'address'      => $this->hospital->address,
                        'phone'        => $this->hospital->phone,
                        'is_published' => $this->hospital->is_published,
                        'deleted_at'   => $this->hospital->deleted_at,
                        'created_at'   => $this->hospital->created_at,
                        'updated_at'   => $this->hospital->updated_at,
                    ],
                ],
                ],
                ]);
    }

    /**
     * お気に入り登録 登録されること
     */
    public function testAttachSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);

        // 実行（Act）
        $response = $this->post(route('user.favorites.attach', ['uuid' => $this->hospital->uuid]));

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('favorites', 1)
            ->assertDatabaseHas('favorites', [
            'user_id'     => $this->user->id,
            'hospital_id' => $this->hospital->id,
        ]);
    }

    /**
     * お気に入り登録 重複して登録されないこと
     */
    public function testAttachNotDuplicateSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        Favorite::factory()->create([
            'user_id'     => $this->user->id,
            'hospital_id' => $this->hospital->id,
        ]);

        // 実行（Act）
        $response = $this->post(route('user.favorites.attach', ['uuid' => $this->hospital->uuid]));

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('favorites', 1)
            ->assertDatabaseHas('favorites', [
                'user_id'     => $this->user->id,
                'hospital_id' => $this->hospital->id,
            ]);
    }

    /**
     * お気に入り解除 解除されること
     */
    public function testDetachSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        Favorite::factory()->create([
            'user_id'     => $this->user->id,
            'hospital_id' => $this->hospital->id,
        ]);

        // 実行（Act）
        $response = $this->delete(route('user.favorites.detach', ['uuid' => $this->hospital->uuid]));

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('favorites', 0);
    }

    /**
     * お気に入り解除 お気に入り登録していない病院を指定してもエラーにならないこと
     */
    public function testDetachNotAttachSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);

        // 実行（Act）
        $response = $this->delete(
            route('user.favorites.detach', ['uuid' => $this->hospital->uuid]), // お気に入り登録していない病院を指定
        );

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('favorites', 0);
    }
}
