<?php

declare(strict_types=1);

namespace Feature\Controllers\User;

use App\Domains\Review\Enum\Rating;
use App\Models\Hospital;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewControllerTest extends TestCase
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
     * レビュー一覧 病院のレビューのみが取得できていること
     */
    public function testIndexSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        $reviews = Review::factory(2)->create([
            'hospital_id' => $this->hospital->id,
            'user_id'     => $this->user->id,
        ]);
        Review::factory()->create(); // 他院のレビューを作成

        // 実行（Act）
        $response = $this->get(route('user.hospital.reviews.index', ['hospitalUuid' => $this->hospital->uuid]));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment([
                'uuid'   => $reviews[1]->uuid,
                'rating' => $reviews[1]->rating,
                'title'  => $reviews[1]->title,
                'body'   => $reviews[1]->body,
            ])
            ->assertJsonFragment([
                'uuid'   => $reviews[0]->uuid,
                'rating' => $reviews[0]->rating,
                'title'  => $reviews[0]->title,
                'body'   => $reviews[0]->body,
            ])
            ->assertJsonPath('data.0.uuid', (string)$reviews[1]->uuid) // 新着順で取得される
            ->assertJsonPath('data.1.uuid', (string)$reviews[0]->uuid);
    }

    /**
     * レビューの登録 新規登録できていること
     */
    public function testStoreSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        $postData = [
            'rating' => Rating::One->value,
            'title'  => 'テストタイトル',
            'body'   => 'テスト本文',
        ];

        // 実行（Act）
        $response = $this->post(route('user.hospital.reviews.store', ['hospitalUuid' => $this->hospital->uuid]), $postData);

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('reviews', 1);

        $record = Review::first();
        $this->assertEquals($this->hospital->id, $record->hospital_id);
        $this->assertEquals($this->user->id, $record->user_id);
        $this->assertEquals($postData['rating'], $record->rating->value);
        $this->assertEquals($postData['title'], $record->title);
        $this->assertEquals($postData['body'], $record->body);
    }

    /**
     * レビューの詳細 情報が取得できていること
     */
    public function testShowSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        $review = Review::factory()->create([
            'hospital_id' => $this->hospital->id,
        ]);

        // 実行（Act）
        $response = $this->get(route(
            'user.hospital.reviews.show',
            ['hospitalUuid' => $this->hospital->uuid, 'uuid' => $review->uuid],
        ));

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('reviews', 1);

        $record = Review::first();
        $this->assertEquals($review->hospital_id, $record->hospital_id);
        $this->assertEquals($review->user_id, $record->user_id);
        $this->assertEquals($review->rating, $record->rating);
        $this->assertEquals($review->title, $record->title);
        $this->assertEquals($review->body, $record->body);
    }

    /**
     * レビューの更新 情報が更新できていること
     */
    public function testUpdateSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        $review = Review::factory()->create([
            'hospital_id' => $this->hospital->id,
            'user_id'     => $this->user->id,
        ]);
        $postData = [
            'rating' => Rating::One->value,
            'title'  => 'テストタイトル',
            'body'   => 'テスト本文',
        ];

        // 実行（Act）
        $response = $this->put(route(
            'user.hospital.reviews.update',
            ['hospitalUuid' => $this->hospital->uuid, 'uuid' => $review->uuid,],
        ), $postData);

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('reviews', 1);

        $record = Review::first();
        $this->assertEquals($this->hospital->id, $record->hospital_id);
        $this->assertEquals($this->user->id, $record->user_id);
        $this->assertEquals($postData['rating'], $record->rating->value);
        $this->assertEquals($postData['title'], $record->title);
        $this->assertEquals($postData['body'], $record->body);
    }

    /**
     * レビューの更新 他のユーザーのレビューを更新できないこと
     */
    public function testUpdateNotOwnFailure()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        $review = Review::factory()->create([
            'hospital_id' => $this->hospital->id,
            'user_id'     => User::factory()->create()->id, // 他のユーザー
        ]);
        $postData = [
            'rating' => Rating::One->value,
            'title'  => 'テストタイトル',
            'body'   => 'テスト本文',
        ];

        // 実行（Act）
        $response = $this->put(route(
            'user.hospital.reviews.update',
            ['hospitalUuid' => $this->hospital->uuid, 'uuid' => $review->uuid,],
        ), $postData);

        // 検証（Assert）
        $response->assertStatus(404);
    }

    /**
     * レビューの更新 病院のuuidが間違っている場合に404が返されること
     */
    public function testUpdateInvalidHospitalUuidFailure()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        $review = Review::factory()->create([
            'hospital_id' => Hospital::factory()->create()->id, // 他の病院
            'user_id'     => $this->user->id,
        ]);
        $postData = [
            'rating' => Rating::One->value,
            'title'  => 'テストタイトル',
            'body'   => 'テスト本文',
        ];

        // 実行（Act）
        $response = $this->put(route(
            'user.hospital.reviews.update',
            ['hospitalUuid' => $this->hospital->uuid, 'uuid' => $review->uuid,],
        ), $postData);

        // 検証（Assert）
        $response->assertStatus(404);
    }

    /**
     * ログインユーザーが投稿したレビューの一覧 ログインユーザーのレビューのみが取得できていること
     */
    public function testIndexOwn()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        $reviews = Review::factory(2)->create([
            'hospital_id' => $this->hospital->id,
            'user_id'     => $this->user->id,
        ]);
        Review::factory()->create([ // 他のユーザーのレビューを作成
            'user_id' => User::factory()->create()->id,
            ]);

        // 実行（Act）
        $response = $this->get(route('user.reviews.index'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment([
                'uuid'   => $reviews[1]->uuid,
                'rating' => $reviews[1]->rating,
                'title'  => $reviews[1]->title,
                'body'   => $reviews[1]->body,
            ])
            ->assertJsonFragment([
                'uuid'   => $reviews[0]->uuid,
                'rating' => $reviews[0]->rating,
                'title'  => $reviews[0]->title,
                'body'   => $reviews[0]->body,
            ])
            ->assertJsonPath('data.0.uuid', (string)$reviews[1]->uuid) // 新着順で取得される
            ->assertJsonPath('data.1.uuid', (string)$reviews[0]->uuid);
    }
}
