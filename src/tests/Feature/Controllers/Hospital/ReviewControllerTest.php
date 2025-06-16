<?php

declare(strict_types=1);

namespace Feature\Controllers\Hospital;

use App\Models\Hospital;
use App\Models\Review;
use App\Models\StaffMember;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    private $guard = 'staff-members';

    public function setUp():void
    {
        parent::setUp();

        $this->hospital = Hospital::factory()->create();

        $this->staff = StaffMember::factory()->create([
            'hospital_id' => $this->hospital->id,
            ]);
    }

    /**
     * レビュー一覧 新着順でレビューが取得できること
     */
    public function testIndexDefaultSortSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->staff, $this->guard);
        $reviews = Review::factory(2)->create([
            'hospital_id' => $this->hospital->id,
            'user_id'     => User::factory()->create(),
        ]);
        Review::factory()->create([ // 他院のレビューを作成
            'hospital_id' => Hospital::factory()->create(),
            'user_id'     => User::factory()->create(),
        ]);

        // 実行（Act）
        $response = $this->get(route('hospital.reviews.index'));

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
