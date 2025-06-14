<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Hospital;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $menus = [
            ['name' => '一般診察', 'detail' => '基本的な診察を行います。健康状態のチェックや病気の早期発見をサポートします。'],
            ['name' => '健康診断', 'detail' => '年に一度の健康診断で、体調をチェックします。'],
            ['name' => 'ワクチン接種', 'detail' => '各種ワクチンを接種し、病気を予防します。'],
            ['name' => '避妊・去勢手術', 'detail' => '適切な時期に安全な手術を行います。'],
            ['name' => '歯科治療', 'detail' => '歯周病や歯石除去など、口腔ケアを行います。'],
            ['name' => '皮膚科診療', 'detail' => '皮膚のトラブルに対応します。かゆみや湿疹の治療を行います。'],
            ['name' => '腫瘍治療', 'detail' => '腫瘍の診断と治療を専門的に行います。'],
        ];
        $menu = $this->faker->randomElement($menus);
        return [
            'uuid'          => Str::uuid(),
            'hospital_id'   => Hospital::inRandomOrder()->first(),
            'name'          => $menu['name'],
            'detail'        => $menu['detail'],
            'required_time' => 30,
            'is_published'  => true,
        ];
    }
}
