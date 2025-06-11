<?php

declare(strict_types=1);

namespace App\Http\Requests\Hospital\Menu;

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

    public function rules(): array
    {
        return [
            'name'          => 'required|string',
            'detail'        => 'required|string',
            'required_time' => 'required|integer|max:300',
            'is_published'  => 'required|boolean',
        ];
    }

    public function getName(): string
    {
        return $this->validated('name');
    }

    public function getDetail(): string
    {
        return $this->validated('detail');
    }

    public function getRequiredTime(): int
    {
        return $this->validated('required_time');
    }

    public function isPublished(): bool
    {
        return $this->validated('is_published');
    }
}
