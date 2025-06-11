<?php

declare(strict_types=1);

namespace Tests\Unit\QueryService\Hospital;

use App\Infrastructure\QueryService\ReviewQueryServiceInterface;
use App\Models\Hospital;
use App\Models\Review;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReviewQueryServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hospital = Hospital::factory()->create();

        $this->reviewQueryService = app(ReviewQueryServiceInterface::class);
    }

    /**
     * 病院のuuidでレビュー一覧を取得する
     */
    public function testListByHospitalUuidSuccess()
    {
        // 準備（Arrange）
        Review::factory(2)->create([
            'hospital_id' => $this->hospital->id,
        ]);
        Review::factory()->create(); // 他院のレビューを作成

        // 実行（Act）
        $paginator = $this->reviewQueryService->listByHospitalUuid(
            (string)$this->hospital->uuid,  // 対象の病院
            1,  // ページ番号
            50, // 1ページあたりの表示数
            '', // 検索キーワード
            [], // 評価点数
            [], // 並び替え
            [], // クエリパラメータ
        );

        // 検証（Assert）
        $this->assertCount(2, $paginator->getCollection());
    }
}
