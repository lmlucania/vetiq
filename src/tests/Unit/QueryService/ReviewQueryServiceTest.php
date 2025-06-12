<?php

declare(strict_types=1);

namespace Tests\Unit\QueryService;

use App\Domains\Review\Enum\Rating;
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
        foreach (range(1, 5) as $i) {
            Review::factory()->create([
                'id'          => $i,
                'hospital_id' => $this->hospital->id,
            ]);
        }
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
        $reviews = $paginator->getCollection();
        $this->assertCount(5, $reviews);
        $this->assertSame([5, 4, 3, 2, 1], $reviews->pluck('id')->all());
    }

    /**
     * 病院のuuidでレビュー一覧を取得する
     * テスト対象：ページネーションのページ番号
     */
    public function testListByHospitalUuidPaginatorPageSuccess()
    {
        // 準備（Arrange）
        Review::factory(60)->create([
            'hospital_id' => $this->hospital->id,
        ]);
        Review::factory()->create(); // 他院のレビューを作成

        // 実行（Act）
        $paginator = $this->reviewQueryService->listByHospitalUuid(
            (string)$this->hospital->uuid,  // 対象の病院
            2,  // ページ番号（テスト対象）
            50, // 1ページあたりの表示数
            '', // 検索キーワード
            [], // 評価点数
            [], // 並び替え
            [], // クエリパラメータ
        );

        // 検証（Assert）
        $this->assertCount(10, $paginator->getCollection());
    }

    /**
     * 病院のuuidでレビュー一覧を取得する
     * テスト対象：ページネーションの1ページあたりの表示数
     */
    public function testListByHospitalUuidPaginatorPerPageSuccess()
    {
        // 準備（Arrange）
        Review::factory(60)->create([
            'hospital_id' => $this->hospital->id,
        ]);
        Review::factory()->create(); // 他院のレビューを作成

        // 実行（Act）
        $paginator = $this->reviewQueryService->listByHospitalUuid(
            (string)$this->hospital->uuid,  // 対象の病院
            1,  // ページ番号
            10, // 1ページあたりの表示数（テスト対象）
            '', // 検索キーワード
            [], // 評価点数
            [], // 並び替え
            [], // クエリパラメータ
        );

        // 検証（Assert）
        $this->assertCount(10, $paginator->getCollection());
    }

    /**
     * 病院のuuidでレビュー一覧を取得する タイトルで部分一致
     * テスト対象：キーワード検索
     */
    public function testListByHospitalUuidMatchesKeywordInTitleSuccess()
    {
        // 準備（Arrange）
        Review::factory()->create([
            'hospital_id' => $this->hospital->id,
            'title'       => 'あテスト',
        ]);
        Review::factory()->create([
            'hospital_id' => $this->hospital->id,
            'title'       => 'テストあ',
        ]);
        Review::factory()->create([
            'hospital_id' => $this->hospital->id,
            'title'       => 'あテストあ',
        ]);
        Review::factory()->create([
            'hospital_id' => $this->hospital->id,
            'title'       => 'あいうえお',
        ]);
        Review::factory()->create(['title' => 'テスト']); // 他院のレビューを作成

        // 実行（Act）
        $paginator = $this->reviewQueryService->listByHospitalUuid(
            (string)$this->hospital->uuid,  // 対象の病院
            1,  // ページ番号
            50, // 1ページあたりの表示数
            'テスト', // 検索キーワード（テスト対象）
            [], // 評価点数
            [], // 並び替え
            [], // クエリパラメータ
        );

        // 検証（Assert）
        $reviews = $paginator->getCollection();
        $this->assertCount(3, $reviews);
        $this->assertSame(['あテストあ', 'テストあ', 'あテスト'], $reviews->pluck('title')->all());
    }

    /**
     * 病院のuuidでレビュー一覧を取得する 本文で部分一致
     * テスト対象：キーワード検索
     */
    public function testListByHospitalUuidMatchesKeywordInBodySuccess()
    {
        // 準備（Arrange）
        Review::factory()->create([
            'hospital_id' => $this->hospital->id,
            'body'        => 'あテスト',
        ]);
        Review::factory()->create([
            'hospital_id' => $this->hospital->id,
            'body'        => 'テストあ',
        ]);
        Review::factory()->create([
            'hospital_id' => $this->hospital->id,
            'body'        => 'あテストあ',
        ]);
        Review::factory()->create([
            'hospital_id' => $this->hospital->id,
            'body'        => 'あいうえお',
        ]);
        Review::factory()->create(['body' => 'テスト']); // 他院のレビューを作成

        // 実行（Act）
        $paginator = $this->reviewQueryService->listByHospitalUuid(
            (string)$this->hospital->uuid,  // 対象の病院
            1,  // ページ番号
            50, // 1ページあたりの表示数
            'テスト', // 検索キーワード（テスト対象）
            [], // 評価点数
            [], // 並び替え
            [], // クエリパラメータ
        );

        // 検証（Assert）
        $reviews = $paginator->getCollection();
        $this->assertCount(3, $reviews);
        $this->assertSame(['あテストあ', 'テストあ', 'あテスト'], $reviews->pluck('body')->all());
    }

    /**
     * 病院のuuidでレビュー一覧を取得する 評価点数で絞り込み（一つ指定）
     * テスト対象：評価点数
     */
    public function testListByHospitalUuidMatchesRatingSingleSuccess()
    {
        // 準備（Arrange）
        Review::factory()->create([
            'hospital_id' => $this->hospital->id,
            'rating'      => Rating::Zero->value,
        ]);
        Review::factory()->create([
            'hospital_id' => $this->hospital->id,
            'rating'      => Rating::Zero->value,
        ]);
        Review::factory()->create([
            'hospital_id' => $this->hospital->id,
            'rating'      => Rating::One->value,
        ]);
        Review::factory()->create([
            'hospital_id' => $this->hospital->id,
            'rating'      => Rating::Two->value,
        ]);
        Review::factory()->create(['rating' => Rating::Zero->value,]); // 他院のレビューを作成

        // 実行（Act）
        $paginator = $this->reviewQueryService->listByHospitalUuid(
            (string)$this->hospital->uuid,  // 対象の病院
            1,  // ページ番号
            50, // 1ページあたりの表示数
            '', // 検索キーワード
            [Rating::Zero], // 評価点数（テスト対象）
            [], // 並び替え
            [], // クエリパラメータ
        );

        // 検証（Assert）
        $reviews = $paginator->getCollection();
        $this->assertCount(2, $reviews);
        $this->assertEquals(Rating::Zero, $reviews[0]->rating);
        $this->assertEquals(Rating::Zero, $reviews[1]->rating);
    }

    /**
     * 病院のuuidでレビュー一覧を取得する 評価点数で絞り込み（複数指定）
     * テスト対象：評価点数
     */
    public function testListByHospitalUuidMatchesRatingMultiSuccess()
    {
        // 準備（Arrange）
        Review::factory()->create([
            'hospital_id' => $this->hospital->id,
            'rating'      => Rating::Zero->value, // 絞り込み条件に一致
        ]);
        Review::factory()->create([
            'hospital_id' => $this->hospital->id,
            'rating'      => Rating::One->value, // 絞り込み条件に一致
        ]);
        Review::factory()->create([
            'hospital_id' => $this->hospital->id,
            'rating'      => Rating::Two->value, // 絞り込み条件に不一致
        ]);
        Review::factory()->create([
            'hospital_id' => $this->hospital->id,
            'rating'      => Rating::Three->value, // 絞り込み条件に不一致
        ]);
        Review::factory()->create(['rating' => Rating::Zero->value,]); // 他院のレビューを作成

        // 実行（Act）
        $paginator = $this->reviewQueryService->listByHospitalUuid(
            (string)$this->hospital->uuid,  // 対象の病院
            1,  // ページ番号
            50, // 1ページあたりの表示数
            '', // 検索キーワード
            [Rating::Zero, Rating::One], // 評価点数（テスト対象）
            [], // 並び替え
            [], // クエリパラメータ
        );

        // 検証（Assert）
        $reviews = $paginator->getCollection();
        $this->assertCount(2, $reviews);
        $this->assertSame([Rating::One, Rating::Zero], $reviews->pluck('rating')->all());
    }

    /**
     * 病院のuuidでレビュー一覧を取得する idの昇順で並び替え
     * テスト対象：並び替え
     */
    public function testListByHospitalUuidSortByIdAscSuccess()
    {
        // 準備（Arrange）
        Review::factory()->create([
            'id'          => 3, // 並び替えの対象
            'hospital_id' => $this->hospital->id,
        ]);
        Review::factory()->create([
            'id'          => 2, // 並び替えの対象
            'hospital_id' => $this->hospital->id,
        ]);
        Review::factory()->create([
            'id'          => 1, // 並び替えの対象
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
            ['id'], // 並び替え（テスト対象）
            [], // クエリパラメータ
        );

        // 検証（Assert）
        $reviews = $paginator->getCollection();
        $this->assertCount(3, $reviews);
        $this->assertSame([1, 2, 3], $reviews->pluck('id')->all());
    }

    /**
     * 病院のuuidでレビュー一覧を取得する idの降順で並び替え
     * テスト対象：並び替え
     */
    public function testListByHospitalUuidSortByIdDescSuccess()
    {
        // 準備（Arrange）
        Review::factory()->create([
            'id'          => 3, // 並び替えの対象
            'hospital_id' => $this->hospital->id,
        ]);
        Review::factory()->create([
            'id'          => 2, // 並び替えの対象
            'hospital_id' => $this->hospital->id,
        ]);
        Review::factory()->create([
            'id'          => 1, // 並び替えの対象
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
            ['-id'], // 並び替え（テスト対象）
            [], // クエリパラメータ
        );

        // 検証（Assert）
        $reviews = $paginator->getCollection();
        $this->assertCount(3, $reviews);
        $this->assertSame([3, 2, 1], $reviews->pluck('id')->all());
    }

    /**
     * 病院のuuidでレビュー一覧を取得する ratingの昇順で並び替え
     * テスト対象：並び替え
     */
    public function testListByHospitalUuidSortByRatingAscSuccess()
    {
        // 準備（Arrange）
        Review::factory()->create([
            'rating'      => Rating::Zero, // 並び替えの対象
            'hospital_id' => $this->hospital->id,
        ]);
        Review::factory()->create([
            'rating'      => Rating::One, // 並び替えの対象
            'hospital_id' => $this->hospital->id,
        ]);
        Review::factory()->create([
            'rating'      => Rating::Two, // 並び替えの対象
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
            ['rating'], // 並び替え（テスト対象）
            [], // クエリパラメータ
        );

        // 検証（Assert）
        $reviews = $paginator->getCollection();
        $this->assertCount(3, $reviews);
        $this->assertSame([Rating::Zero, Rating::One, Rating::Two], $reviews->pluck('rating')->all());
    }

    /**
     * 病院のuuidでレビュー一覧を取得する ratingの降順で並び替え
     * テスト対象：並び替え
     */
    public function testListByHospitalUuidSortByRatingDescSuccess()
    {
        // 準備（Arrange）
        Review::factory()->create([
            'rating'      => Rating::Zero, // 並び替えの対象
            'hospital_id' => $this->hospital->id,
        ]);
        Review::factory()->create([
            'rating'      => Rating::One, // 並び替えの対象
            'hospital_id' => $this->hospital->id,
        ]);
        Review::factory()->create([
            'rating'      => Rating::Two, // 並び替えの対象
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
            ['-rating'], // 並び替え（テスト対象）
            [], // クエリパラメータ
        );

        // 検証（Assert）
        $reviews = $paginator->getCollection();
        $this->assertCount(3, $reviews);
        $this->assertSame([Rating::Two, Rating::One, Rating::Zero], $reviews->pluck('rating')->all());
    }

    /**
     * 病院のuuidでレビュー一覧を取得する rating昇順とid降順で並び替え
     * テスト対象：並び替え
     */
    public function testListByHospitalUuidMultiSortByRatingDescAndIdAscSuccess()
    {
        // 準備（Arrange）
        Review::factory()->create([
            'id'          => 1, // 並び替えの対象
            'rating'      => Rating::Zero, // 並び替えの対象
            'hospital_id' => $this->hospital->id,
        ]);
        Review::factory()->create([
            'id'          => 2, // 並び替えの対象
            'rating'      => Rating::One, // 並び替えの対象
            'hospital_id' => $this->hospital->id,
        ]);
        Review::factory()->create([
            'id'          => 3, // 並び替えの対象
            'rating'      => Rating::One, // 並び替えの対象
            'hospital_id' => $this->hospital->id,
        ]);
        Review::factory()->create([
            'id'          => 4, // 並び替えの対象
            'rating'      => Rating::Two, // 並び替えの対象
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
            ['-rating', 'id'], // 並び替え（テスト対象）
            [], // クエリパラメータ
        );

        // 検証（Assert）
        $reviews = $paginator->getCollection();
        $this->assertCount(4, $reviews);
        $this->assertSame([4, 2, 3, 1], $reviews->pluck('id')->all());
    }

    /**
     * 病院のuuidでレビュー一覧を取得する rating降順とid降順で並び替え
     * テスト対象：並び替え
     */
    public function testListByHospitalUuidMultiSortByRatingDescAndIdDescSuccess()
    {
        // 準備（Arrange）
        Review::factory()->create([
            'id'          => 1, // 並び替えの対象
            'rating'      => Rating::Zero, // 並び替えの対象
            'hospital_id' => $this->hospital->id,
        ]);
        Review::factory()->create([
            'id'          => 2, // 並び替えの対象
            'rating'      => Rating::One, // 並び替えの対象
            'hospital_id' => $this->hospital->id,
        ]);
        Review::factory()->create([
            'id'          => 3, // 並び替えの対象
            'rating'      => Rating::One, // 並び替えの対象
            'hospital_id' => $this->hospital->id,
        ]);
        Review::factory()->create([
            'id'          => 4, // 並び替えの対象
            'rating'      => Rating::Two, // 並び替えの対象
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
            ['-rating', '-id'], // 並び替え（テスト対象）
            [], // クエリパラメータ
        );

        // 検証（Assert）
        $reviews = $paginator->getCollection();
        $this->assertCount(4, $reviews);
        $this->assertSame([4, 3, 2, 1], $reviews->pluck('id')->all());
    }
}
