<?php

declare(strict_types=1);

namespace Feature\Controllers\User;

use App\Domains\Location\Enum\Prefecture;
use App\Domains\Pet\Enum\Gender;
use App\Models\Hospital;
use App\Models\Pet;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HospitalControllerTest extends TestCase
{
    use RefreshDatabase;

    private string $guard = 'users';

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

    }

    /**
     * 病院の一覧 キーワード検索
     * 名前で部分一致検索ができること
     */
    public function testIndexSearchByKeywordSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        Hospital::factory()->create([
            'name' => 'test'
        ]);
        Hospital::factory()->create([
            'name' => 'xxx'
        ]);

        // 実行（Act）
        $response = $this->get(route('user.hospitals.index', ['keyword' => 'es']));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'name'         => 'test',
            ]);
    }
}
