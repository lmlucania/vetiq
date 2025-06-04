<?php

declare(strict_types=1);

namespace Feature\Controllers\User;

use App\Domains\Location\Enum\Prefecture;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $guard = 'users';

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * 個人情報を取得
     * user_profileにレコードがない場合
     */
    public function testMeNotExistUserProfileSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);

        // 実行（Act）
        $response = $this->get(route('user.profile.me'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonFragment([
            'data' => [
                    'email'           => $this->user->email,
                    'first_name'      => null,
                    'last_name'       => null,
                    'first_name_kana' => null,
                    'last_name_kana'  => null,
                    'phone_number'    => null,
                    'post_code'       => null,
                    'prefecture'      => null,
                    'address1'        => null,
                    'address2'        => null,
                ],
                ]);
    }

    /**
     * 個人情報を取得
     * user_profileにレコードがある場合
     */
    public function testMeExistUserProfileSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        $profile = UserProfile::factory()->create(['user_id' => $this->user->id]);

        // 実行（Act）
        $response = $this->get(route('user.profile.me'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonFragment([
                'data' => [
                    'email'           => $this->user->email,
                    'first_name'      => $profile->first_name,
                    'last_name'       => $profile->last_name,
                    'first_name_kana' => $profile->first_name_kana,
                    'last_name_kana'  => $profile->last_name_kana,
                    'phone_number'    => $profile->phone_number,
                    'post_code'       => $profile->post_code,
                    'prefecture'      => $profile->prefecture,
                    'address1'        => $profile->address1,
                    'address2'        => $profile->address2,
                ],
            ]);
    }

    /**
     * 個人情報を取得
     * user_profileにレコードがない場合
     */
    public function testUpdateNotExistUserProfileSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        $postData = [
            'email'           => 'user+1@mail.com',
            'first_name'      => '陽一',
            'last_name'       => '伊藤',
            'first_name_kana' => 'アスカ',
            'last_name_kana'  => 'ミヤケ',
            'phone_number'    => '0123456789',
            'post_code'       => '2675423',
            'prefecture'      => Prefecture::Okinawa->value,
            'address1'        => '田辺町津田9-8-6',
            'address2'        => 'ハイツ杉山104号',
        ];

        // 実行（Act）
        $response = $this->post(route('user.profile.update'), $postData);

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertEquals($postData['email'], User::first()->email);

        $this->assertDatabaseCount('user_profiles', 1);

        $record = UserProfile::first();
        $this->assertEquals($postData['first_name'], $record->first_name);
        $this->assertEquals($postData['last_name'], $record->last_name);
        $this->assertEquals($postData['first_name_kana'], $record->first_name_kana);
        $this->assertEquals($postData['last_name_kana'], $record->last_name_kana);
        $this->assertEquals($postData['phone_number'], $record->phone_number);
        $this->assertEquals($postData['post_code'], $record->post_code);
        $this->assertEquals($postData['prefecture'], $record->prefecture->value); // モデルでキャストされている
        $this->assertEquals($postData['address1'], $record->address1);
        $this->assertEquals($postData['address2'], $record->address2);
    }

    /**
     * 個人情報を取得
     * user_profileにレコードがある場合
     */
    public function testUpdateExistUserProfileSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        UserProfile::factory()->create(['user_id' => $this->user->id]);
        $postData = [
            'email'           => 'user+1@mail.com',
            'first_name'      => '陽一',
            'last_name'       => '伊藤',
            'first_name_kana' => 'アスカ',
            'last_name_kana'  => 'ミヤケ',
            'phone_number'    => '0123456789',
            'post_code'       => '2675423',
            'prefecture'      => Prefecture::Okinawa->value,
            'address1'        => '田辺町津田9-8-6',
            'address2'        => 'ハイツ杉山104号',
        ];

        // 実行（Act）
        $response = $this->post(route('user.profile.update'), $postData);

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertEquals($postData['email'], User::first()->email);

        $this->assertDatabaseCount('user_profiles', 1);

        $record = UserProfile::first();
        $this->assertEquals($postData['first_name'], $record->first_name);
        $this->assertEquals($postData['last_name'], $record->last_name);
        $this->assertEquals($postData['first_name_kana'], $record->first_name_kana);
        $this->assertEquals($postData['last_name_kana'], $record->last_name_kana);
        $this->assertEquals($postData['phone_number'], $record->phone_number);
        $this->assertEquals($postData['post_code'], $record->post_code);
        $this->assertEquals($postData['prefecture'], $record->prefecture->value); // モデルでキャストされている
        $this->assertEquals($postData['address1'], $record->address1);
        $this->assertEquals($postData['address2'], $record->address2);
    }

    /**
     * 退会 ユーザーが論理削除されていること
     */
    public function testDestroySuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);

        // 実行（Act）
        $response = $this->delete(route('user.profile.destroy'));

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('users', 1);

        $this->assertSoftDeleted(
            table: 'users',
            data: [
                'id' => $this->user->id,
            ],
        );
    }
}
