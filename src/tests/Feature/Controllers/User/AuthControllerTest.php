<?php

declare(strict_types=1);

namespace Feature\Controllers\User;

use App\Models\HospitalModel;
use App\Models\StaffModel;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $guard = 'users';

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'id'          => 1,
            'email'       => 'test@example.com',
            'password'    => Hash::make('password'),
        ]);
    }

    /**
     * ログイン 成功時のレスポンスをチェック
     * @return void
     */
    public function testLoginSuccess()
    {
        // 準備（Arrange）

        // 実行（Act）
        $response = $this->post(route('user.login'), [
            'email'    => 'test@example.com',
            'password' => 'password',
        ]);

        // 検証（Assert）
        $response->assertStatus(200);
    }

    /**
     * ログイン 失敗時のレスポンスをチェック
     * @return void
     */
    public function testLoginFailure()
    {
        // 準備（Arrange）

        // 実行（Act）
        $response = $this->post(route('user.login'), [
            'email'    => 'testxxx@example.com', // テスト対象
            'password' => 'password',
        ]);

        // 検証（Assert）
        $response
            ->assertStatus(401)
            ->assertJsonCount(2)
            ->assertJsonFragment([
                'error'   => 'Unauthorized',
                'message' => 'ログインに失敗しました。メールアドレスまたはパスワードが正しくありません。',
            ]);
    }

    /**
     * ログイン 論理削除済みのユーザーでログインできないことをチェック
     * @return void
     */
    public function testLoginWithSoftDeletedStaffFailure()
    {
        // 準備（Arrange）
        $this->user->delete();

        // 実行（Act）
        $response = $this->post(route('user.login'), [
            'email'    => 'test@example.com',
            'password' => 'password',
        ]);

        // 検証（Assert）
        $response
            ->assertStatus(401)
            ->assertJsonCount(2)
            ->assertJsonFragment([
                'error'   => 'Unauthorized',
                'message' => 'ログインに失敗しました。メールアドレスまたはパスワードが正しくありません。',
            ]);
    }

    /**
     * ログイン バリデーションエラー emailが空文字
     * @return void
     */
    public function testLoginEmailEmptyValidationError()
    {
        // 準備（Arrange）
        $postData = [
            'email'    => '', // テスト対象
            'password' => 'password',
        ];

        // 実行（Act）
        $response = $this->post(route('user.login'), $postData);

        // 検証（Assert）
        $response
            ->assertStatus(422)
            ->assertJsonCount(2)
            ->assertJsonFragment([
                'message' => 'バリデーションエラー',
                'errors'  => [
                    'email' => ['validation.required'],
                ],
            ]);
    }

    /**
     * ログイン バリデーションエラー passwordが空文字
     * @return void
     */
    public function testLoginPasswordEmptyValidationError()
    {
        // 準備（Arrange）
        $postData = [
            'email'    => 'test@example.com',
            'password' => '', // テスト対象
        ];

        // 実行（Act）
        $response = $this->post(route('user.login'), $postData);

        // 検証（Assert）
        $response
            ->assertStatus(422)
            ->assertJsonCount(2)
            ->assertJsonFragment([
                'message' => 'バリデーションエラー',
                'errors'  => [
                    'password' => ['validation.required'],
                ],
            ]);
    }

    /**
     * ログアウト レスポンスをチェック
     * @return void
     */
    public function testUserLogoutSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);

        // 実行（Act）
        $response = $this->post(route('user.logout'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJson([
                'message' => 'ログアウトが成功しました。',
            ]);
    }

    /**
     * ログアウト 未ログインユーザー 401が返されることをチェック
     * @return void
     */
    public function testUnauthorizedUserLogoutFailure()
    {
        // 準備（Arrange）

        // 実行（Act）
        $response = $this->post(route('user.logout'));

        // 検証（Assert）
        $response->assertStatus(401);
    }
}
