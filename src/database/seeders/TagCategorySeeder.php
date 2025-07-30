<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\TagCategory;
use Illuminate\Database\Seeder;

class TagCategorySeeder extends Seeder
{
    private const SAMPLE_DATA = [
        '診療内容' => [
            '夜間診療',
            '日曜診療',
            '緊急対応',
            '予防接種',
            '避妊・去勢手術',
            '健康診断',
            'ワクチン接種',
            '皮膚科',
        ],
        '雰囲気' => [
            '明朗会計',
            '女性スタッフ',
            '清潔な院内',
            '待ち時間が短い',
            '親切な対応',
            'ペットに優しい',
            '静かな雰囲気',
        ],
        '専門性' => [
            '猫専門',
            '犬専門',
            'エキゾチックアニマル対応',
            '外科専門',
            '腫瘍科',
            '循環器科',
            '眼科',
            '整形外科',
        ],
        'サービス' => [
            'ペットホテル併設',
            'トリミング対応',
            'オンライン相談',
            'クレジットカード可',
            '駐車場完備',
            '送迎サービス',
            '予約優先',
        ],
        '設備' => [
            '最新医療設備',
            'CT完備',
            'ICU完備',
            '入院設備あり',
            'リハビリルームあり',
        ],
    ];
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryOrder = 1;
        foreach (self::SAMPLE_DATA as $categoryName => $tagNames) {
            $category = TagCategory::create([
                'name'          => $categoryName,
                'display_order' => $categoryOrder++,
            ]);

            $tagOrder = 1;
            foreach ($tagNames as $tagName) {
                Tag::factory()->create([
                    'tag_category_id' => $category,
                    'name'            => $tagName,
                    'display_order'   => $tagOrder++,
                ]);
            }
        }
    }
}
