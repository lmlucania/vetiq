<?php

declare(strict_types=1);

namespace Feature\Controllers\User;

use App\Domains\Pet\Enum\Gender;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PetControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $guard = 'users';

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->pet = Pet::factory()->create([
            'user_id' => $this->user->id,
        ]);
    }

    /**
     * ユーザーのペット一覧 ユーザーのペットが取得できていること
     */
    public function testIndexSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        $pet = Pet::factory()->create([
            'user_id' => $this->user->id,
        ]);
        Pet::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);

        // 実行（Act）
        $response = $this->get(route('user.pets.index'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment([
                'data' => [
                    [
                        'uuid'            => $this->pet->uuid,
                        'name'            => $this->pet->name,
                        'gender'          => $this->pet->gender,
                        'birthday'        => $this->pet->birthday->format('Y-m-d'),
                        'started_care_at' => $this->pet->started_care_at->format('Y-m-d'),
                        'remark'          => $this->pet->remark,
                    ],
                    [
                        'uuid'            => $pet->uuid,
                        'name'            => $pet->name,
                        'gender'          => $pet->gender,
                        'birthday'        => $pet->birthday->format('Y-m-d'),
                        'started_care_at' => $pet->started_care_at->format('Y-m-d'),
                        'remark'          => $pet->remark,
                    ],
                ]]);
    }

    /**
     * ペットの登録 新規登録できていること
     */
    public function testStoreSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        Pet::query()->forceDelete();
        $postData = [
            'name'            => 'テスト名前',
            'gender'          => Gender::Male->value,
            'birthday'        => '2000-01-01',
            'started_care_at' => '2001-01-01',
            'remark'          => 'テスト備考',
        ];

        // 実行（Act）
        $response = $this->post(route('user.pets.store'), $postData);

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('pets', 1);

        $record = Pet::first();
        $this->assertEquals($postData['name'], $record->name);
        $this->assertEquals($postData['gender'], $record->gender->value); // モデルでキャストされている
        $this->assertEquals($postData['birthday'], $record->birthday->format('Y-m-d')); // モデルでキャストされている
        $this->assertEquals($postData['started_care_at'], $record->started_care_at->format('Y-m-d')); // モデルでキャストされている
        $this->assertEquals($postData['remark'], $record->remark);
    }

    /**
     * ペットの詳細 情報が取得できていること
     */
    public function testShowSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);

        // 実行（Act）
        $response = $this->get(route('user.pets.show', ['pet' => $this->pet->uuid]));

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('pets', 1);

        $record = Pet::first();
        $this->assertEquals($this->pet->name, $record->name);
        $this->assertEquals($this->pet->gender, $record->gender);
        $this->assertEquals($this->pet->birthday, $record->birthday);
        $this->assertEquals($this->pet->started_care_at, $record->started_care_at);
        $this->assertEquals($this->pet->remark, $record->remark);
    }

    /**
     * ペットの詳細 他ユーザーのペットが取得できないこと
     */
    public function testShowNotMyPetFailure()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        $otherPet = Pet::factory()->create([
            'user_id' => User::factory()->create()->id,
        ]);

        // 実行（Act）
        $response = $this->get(route('user.pets.show', ['pet' => $otherPet->uuid]));

        // 検証（Assert）
        $response->assertStatus(404);
    }

    /**
     * ペットの更新 更新できていること
     */
    public function testUpdateSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        $postData = [
            'name'            => 'テスト名前',
            'gender'          => Gender::Male->value,
            'birthday'        => '2000-01-01',
            'started_care_at' => '2001-01-01',
            'remark'          => 'テスト備考',
        ];

        // 実行（Act）
        $response = $this->put(route('user.pets.update', ['pet' => $this->pet->uuid]), $postData);

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('pets', 1);

        $record = Pet::first();
        $this->assertEquals($postData['name'], $record->name);
        $this->assertEquals($postData['gender'], $record->gender->value); // モデルでキャストされている
        $this->assertEquals($postData['birthday'], $record->birthday->format('Y-m-d')); // モデルでキャストされている
        $this->assertEquals($postData['started_care_at'], $record->started_care_at->format('Y-m-d')); // モデルでキャストされている
        $this->assertEquals($postData['remark'], $record->remark);
    }

    /**
     * ペットの削除 論理削除ができること
     */
    public function testDeleteSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);

        // 実行（Act）
        $response = $this->delete(route('user.pets.destroy', ['pet' => $this->pet->uuid]));

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('pets', 1);

        $this->assertSoftDeleted(
            table: 'pets',
            data: [
                'id' => $this->pet->id,
            ],
        );
    }
}
