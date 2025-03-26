<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Application\Dto\Response\MenuDto;
use League\Fractal\TransformerAbstract;

class MenuTransformer extends TransformerAbstract
{
    /**
     * @OA\Schema(
     *     schema="Response/Menu",
     *     description="診察メニュー",
     *     type="object",
     *     @OA\Property(
     *         property="uuid",
     *         type="string",
     *         description="診察メニューID",
     *         example="1667cff9-71e5-4719-953c-e074507d2d3d",
     *     ),
     *     @OA\Property(
     *         property="name",
     *         type="string",
     *         description="診察メニュー名",
     *         example="一般診察",
     *     ),
     *     @OA\Property(
     *         property="detail",
     *         type="string",
     *         description="説明",
     *         example="基本的な診察を行います。健康状態のチェックや病気の早期発見をサポートします。",
     *     ),
     *     @OA\Property(
     *         property="required_time",
     *         type="string",
     *         description="所要時間（分）",
     *         example="30",
     *     ),
     *     @OA\Property(
     *         property="is_published",
     *         type="boolean",
     *         description="公開フラグ",
     *         example=true,
     *     )
     * )
     */
    public function transform(MenuDto $menuDto)
    {
        return [
            'uuid'          => $menuDto->getUuid()->getValue(),
            'name'          => $menuDto->getName()->getValue(),
            'detail'        => $menuDto->getDetail()->getValue(),
            'required_time' => $menuDto->getRequiredTime()->getValue(),
            'is_published'  => $menuDto->getIsPublished()->getValue(),
        ];
    }
}
