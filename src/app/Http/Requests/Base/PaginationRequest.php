<?php

declare(strict_types=1);

namespace App\Http\Requests\Base;

class PaginationRequest extends ApiRequest
{
    public function rules(): array
    {
        return [
            'page'     => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:500',
        ];
    }

    /**
     * ページネーションのデフォルト値を設定する
     * @return int
     */
    public function getPage():int
    {
        return (int)$this->page ?: 1;
    }

    /**
     * ページネーションのデフォルト値を設定する
     * @return int
     */
    public function getPerPage():int
    {
        return (int)$this->per_page ?: 50;
    }
}
