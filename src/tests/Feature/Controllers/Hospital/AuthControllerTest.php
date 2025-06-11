<?php

declare(strict_types=1);

namespace Tests\Feature\Controllers\Hospital;

use App\Models\Hospital;
use App\Models\StaffMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $guard = 'staff-members';

    protected function setUp(): void
    {
        parent::setUp();

        $this->staff = StaffMember::factory()->create([
            'id'          => 1,
            'hospital_id' => Hospital::factory()->create()->id,
            'name'        => 'test',
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
        $response = $this->post(route('hospital.login'), [
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
        $response = $this->post(route('hospital.login'), [
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
    public function testLoginWithSoftDeletedStaffMemberFailure()
    {
        // 準備（Arrange）
        $this->staff->delete();

        // 実行（Act）
        $response = $this->post(route('hospital.login'), [
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
        $response = $this->post(route('hospital.login'), $postData);

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
        $response = $this->post(route('hospital.login'), $postData);

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
        $this->actingAs($this->staff, $this->guard);

        // 実行（Act）
        $response = $this->post(route('hospital.logout'));

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
        $response = $this->post(route('hospital.logout'));

        // 検証（Assert）
        $response->assertStatus(401);
    }
}
