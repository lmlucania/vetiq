<?php

declare(strict_types=1);

namespace App\Http\Requests\Hospital\HospitalInfo;

use App\Domains\Location\Enum\Prefecture;
use App\Http\Requests\Base\ApiRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateHospitalRequest extends ApiRequest
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
            'name'         => ['required', 'string', 'max:255'],
            'phone'        => ['required', 'string', 'max:20'],
            'post_code'    => ['required', 'string', 'size:7'],
            'prefecture'   => ['required', 'integer', new Enum(Prefecture::class)],
            'address1'     => ['required', 'string', 'max:255'],
            'address2'     => ['nullable', 'string', 'max:255'],
            'is_published' => ['required', 'boolean'],
        ];
    }

    public function getName(): string
    {
        return $this->validated('name');
    }

    public function getPhone(): string
    {
        return $this->validated('phone');
    }

    public function getPostCode(): string
    {
        return $this->validated('post_code');
    }

    public function getPrefecture(): Prefecture
    {
        return Prefecture::from($this->validated('prefecture'));
    }

    public function getAddress1(): string
    {
        return $this->validated('address1');
    }

    public function getAddress2(): ?string
    {
        return $this->validated('address2');
    }

    public function isPublished(): bool
    {
        return $this->validated('is_published');
    }
}
