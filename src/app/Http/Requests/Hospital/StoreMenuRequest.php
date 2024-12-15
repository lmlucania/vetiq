<?php

declare(strict_types=1);

namespace App\Http\Requests\Hospital;

use App\Http\Requests\Base\ApiRequest;

class StoreMenuRequest extends ApiRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @OA\Schema(
     *     schema="Requests/Hospital/StoreMenuRequest",
     *     type="object",
     *     required={"name", "detail", "required_time", "is_published"},
     *     description="診察メニューの登録",
     *     @OA\Property(
     *          property="name",
     *          type="string",
     *          description="診察メニュー名",
     *          example="健康診断",
     *     ),
     *     @OA\Property(
     *          property="detail",
     *          type="string",
     *          description="診察メニューの説明",
     *          example="年に一度の健康診断で、体調をチェックします。",
     *     ),
     *     @OA\Property(
     *          property="required_time",
     *          type="integer",
     *          description="所要時間（分単位）",
     *          example=30,
     *          maximum=300
     *     ),
     *     @OA\Property(
     *          property="is_published",
     *          type="boolean",
     *          description="公開フラグ"
     *     )
     * )
     */
    public function rules(): array
    {
        return [
            'name'          => 'required|string',
            'detail'        => 'required|string',
            'required_time' => 'required|integer|max:300',
            'is_published'  => 'required|boolean',
        ];
    }
}
