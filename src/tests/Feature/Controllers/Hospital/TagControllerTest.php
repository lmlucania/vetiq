<?php

declare(strict_types=1);

namespace Feature\Controllers\Hospital;

use App\Models\Hospital;
use App\Models\StaffMember;
use App\Models\Tag;
use App\Models\TagCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TagControllerTest extends TestCase
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

        $this->initTagData();
    }

    private function initTagData():void
    {
        Tag::factory()->create([
            'id'              => 11,
            'name'            => 'tag11',
            'display_order'   => 1,
            'tag_category_id' => TagCategory::factory()->create([
                'id' => 1, 'name' => 'category1', 'display_order' => 1,
                ]),
        ]);

        $category = TagCategory::factory()->create([
            'id' => 2, 'name' => 'category2', 'display_order' => 2,
            ]);
        Tag::factory()->create([
            'id'              => 21,
            'name'            => 'tag21',
            'display_order'   => 1,
            'tag_category_id' => $category,
        ]);
        Tag::factory()->create([
            'id'              => 22,
            'name'            => 'tag22',
            'display_order'   => 2,
            'tag_category_id' => $category,
        ]);
    }

    /**
     * 病院に紐づいているタグの一覧 タグカテゴリーとタグが全て取得できること
     */
    public function testIndexSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->staff, $this->guard);
        DB::table('hospital_tag')->insert([
            'hospital_id' => $this->staff->hospital_id,
            'tag_id'      => 11,
        ]);

        // 実行（Act）
        $response = $this->get(route('hospital.tags.index'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment([
                'category_name'          => 'category1',
                'category_display_order' => 1,
            ])
            ->assertJsonFragment([
                'id'                => 11,
                'tag_name'          => 'tag11',
                'tag_display_order' => 1,
                'is_selected'       => true,
            ])
            ->assertJsonFragment([
                'category_name'          => 'category2',
                'category_display_order' => 2,
            ])
            ->assertJsonFragment([
                'id'                => 21,
                'tag_name'          => 'tag21',
                'tag_display_order' => 1,
                'is_selected'       => false,
            ])
            ->assertJsonFragment([
                'id'                => 22,
                'tag_name'          => 'tag22',
                'tag_display_order' => 1,
                'is_selected'       => false,
            ]);
    }

    /**
     * 病院に紐づいているタグの一覧 タグカテゴリーとタグが全て取得できること
     */
    public function testSyncSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->staff, $this->guard);
        $postData = [
            'ids' => [
                11,
                21,
                99, // 存在しないid
            ],
        ];

        // 実行（Act）
        $response = $this->post(route('hospital.tags.sync'), $postData);

        // 検証（Assert）
        $response->assertStatus(200);

        $this->assertDatabaseCount('hospital_tag', 2);
    }
}
