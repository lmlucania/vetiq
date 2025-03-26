<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Application\Dto\Response\VetDto;
use League\Fractal\TransformerAbstract;

class VetTransformer extends TransformerAbstract
{
    /**
     * @OA\Schema(
     *     schema="Response/Vet",
     *     description="獣医師",
     *     type="object",
     *     @OA\Property(
     *         property="uuid",
     *         type="string",
     *         description="獣医師UUID",
     *         example="5705d9e7-39bd-44c4-af36-be78d0eeb825",
     *     ),
     *     @OA\Property(
     *         property="last_name",
     *         type="string",
     *         description="名前（姓）",
     *         example="山田",
     *     ),
     *     @OA\Property(
     *         property="first_name",
     *         type="string",
     *         description="名前（名）",
     *         example="太郎",
     *     ),
     *     @OA\Property(
     *         property="accept_appointment",
     *         type="boolean",
     *         description="指名予約可否フラグ",
     *         example=true,
     *     ),
     *     @OA\Property(
     *         property="remark",
     *         type="string",
     *         description="備考",
     *         example="犬・猫の内科診療を得意としています。",
     *     ),
     * )
     */
    public function transform(VetDto $vetDto)
    {
        return [
            'uuid'               => $vetDto->getUuid()->getValue(),
            'last_name'          => $vetDto->getLastName()->getValue(),
            'first_name'         => $vetDto->getFirstName()->getValue(),
            'accept_appointment' => $vetDto->getAcceptAppointment()->getValue(),
            'remark'             => $vetDto->getRemark()->getValue(),
        ];
    }
}
