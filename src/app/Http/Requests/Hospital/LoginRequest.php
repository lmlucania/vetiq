<?php

declare(strict_types=1);

namespace App\Http\Requests\Hospital;

use App\Http\Requests\Base\ApiRequest;

class LoginRequest extends ApiRequest
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
     * ログイン
     * @lrd:end
     */
    public function rules(): array
    {
        return [
            'email'    => 'required|string|email',
            'password' => 'required|string',
        ];
    }
}
