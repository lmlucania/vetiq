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
     * @lrd:start
     * 診察メニューの登録
     * @lrd:end
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
