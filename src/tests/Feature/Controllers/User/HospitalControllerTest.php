<?php

declare(strict_types=1);

namespace Feature\Controllers\User;

use App\Domains\Location\Enum\Prefecture;
use App\Models\Hospital;
use App\Models\Tag;
use App\Models\TagCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
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
            'name' => 'test',
        ]);
        Hospital::factory()->create([
            'name' => 'xxx',
        ]);

        // 実行（Act）
        $response = $this->get(route('user.hospitals.index', ['keyword' => 'es']));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'name' => 'test',
            ]);
    }

    /**
     * 病院の一覧 タグ検索
     */
    public function testIndexSearchByTagSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        DB::table('hospital_tag')->insert([
            'hospital_id' => Hospital::factory()->create(['name' => 'test'])->id,
            'tag_id'      => Tag::factory()->create([
                'id' => 1, 'tag_category_id' => TagCategory::factory()->create(),
                ])->id,
        ]);
        DB::table('hospital_tag')->insert([
            'hospital_id' => Hospital::factory()->create()->id, // 取得されない病院
            'tag_id'      => Tag::factory()->create([
                'id' => 2, 'tag_category_id' => TagCategory::factory()->create(),
            ])->id,
        ]);

        Hospital::factory()->create(); // 取得されない病院

        // 実行（Act）
        $response = $this->get(route('user.hospitals.index', ['tags' => [1, 9]]));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'name' => 'test',
            ]);
    }

    /**
     * 病院の一覧 都道府県で検索
     */
    public function testIndexSearchByPrefectureSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->user, $this->guard);
        Hospital::factory()->create([
            'name'       => 'test',
            'prefecture' => Prefecture::Hokkaido->value,
        ]);
        Hospital::factory()->create([
            'name'       => 'xxx',
            'prefecture' => Prefecture::Akita->value,
        ]);

        // 実行（Act）
        $response = $this->get(route('user.hospitals.index', ['prefectures' => [Prefecture::Hokkaido->value]]));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonFragment([
                'name' => 'test',
            ]);
    }
}
