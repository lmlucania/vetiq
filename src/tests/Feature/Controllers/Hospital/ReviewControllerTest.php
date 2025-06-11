<?php

declare(strict_types=1);

namespace Feature\Controllers\Hospital;

use App\Domains\Location\Enum\Prefecture;
use App\Models\Hospital;
use App\Models\Review;
use App\Models\StaffMember;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Support\Facades\Hash;

class ReviewControllerTest extends TestCase
{
    use RefreshDatabase;

    private $guard = 'staff-members';

    public function setUp():void
    {
        parent::setUp();

        $this->hospital = Hospital::factory()->create([
            'uuid'         => 'b90612f5-3446-47d7-b66a-12ff54963050',
            'name'         => '裕美子病院',
            'post_code'    => '1234567',
            'prefecture'   => Prefecture::Okinawa->value,
            'address1'     => '佐藤市東区吉本町',
            'address2'     => '佐々木1-2-3',
            'phone'        => '0123456789',
            'is_published' => true,
        ]);

        $this->staff = StaffMember::factory()->create([
            'hospital_id' => $this->hospital->id,
            'name'        => '山岸太一',
            'email'       => 'staff+1@example.com',
            'password'    => Hash::make('password'),
        ]);
    }

    /**
     * レビュー一覧 新着順でレビューが取得できること
     */
    public function testIndexSuccess()
    {
        // 準備（Arrange）
        $this->actingAs($this->staff, $this->guard);
        $review1 = Review::factory()->create([
            'hospital_id' => $this->hospital->id,
            'user_id'     => $this->staff->id,
        ]);
        $review2 = Review::factory()->create([
            'hospital_id' => $this->hospital->id,
        ]);
        Review::factory()->create(); // 他院のレビューを作成

        // 実行（Act）
        $response = $this->get(route('hospital.reviews.index'));

        // 検証（Assert）
        $response
            ->assertStatus(200)
            ->assertJsonCount(2, 'data')
            ->assertJsonFragment([
                'uuid'   => $review2->uuid,
                'rating' => $review2->rating,
                'title'  => $review2->title,
                'body'   => $review2->body,
            ])
            ->assertJsonFragment([
                'uuid'   => $review1->uuid,
                'rating' => $review1->rating,
                'title'  => $review1->title,
                'body'   => $review1->body,
            ])
            ->assertJsonPath('data.0.uuid', (string)$review2->uuid) // 新着順で取得される
            ->assertJsonPath('data.1.uuid', (string)$review1->uuid);
    }
}
