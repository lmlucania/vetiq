<?php

declare(strict_types=1);

namespace App\Http\Requests\Base;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiRequest extends FormRequest
{
    /**
     * バリデーションエラー時の処理
     * @param Validator $validator
     * @return mixed
     */
    protected function failedValidation(Validator $validator)
    {
        $response['message'] = 'バリデーションエラー';
        $response['errors']  = $validator->errors()->toArray();

        throw new HttpResponseException(
            response()->json($response, 422),
        );
    }

    /**
     * rulesでバリデーションできるように、ルートパラメータを含める
     * @return array
     */
    public function validationData()
    {
        // リクエストボディとルートパラメータを統合
        $params      = parent::validationData();
        $routeParams = ['route_params' => $this->route()->parameters()];

        // 上書きしないように、配列を足し算する
        return $params + $routeParams;
    }
}
