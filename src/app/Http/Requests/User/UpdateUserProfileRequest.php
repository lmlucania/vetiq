<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Domains\Location\Enum\Prefecture;
use App\Http\Requests\Base\ApiRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateUserProfileRequest extends ApiRequest
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
            'email'           => ['required', 'string',  'email'],
            'first_name'      => ['required', 'string', 'max:255'],
            'last_name'       => ['required', 'string', 'max:255'],
            'first_name_kana' => ['required', 'string', 'max:255'],
            'last_name_kana'  => ['required', 'string', 'max:255'],
            'phone'           => ['required', 'string', 'max:11'],
            'post_code'       => ['nullable', 'digits:7'],
            'prefecture'      => ['nullable', 'integer', new Enum(Prefecture::class)],
            'address1'        => ['nullable', 'string', 'max:255'],
            'address2'        => ['nullable', 'string', 'max:255'],
        ];
    }

    public function getEmail(): string
    {
        return $this->validated('email');
    }

    public function getFirstName(): string
    {
        return $this->validated('first_name');
    }

    public function getLastName(): string
    {
        return $this->validated('last_name');
    }

    public function getFirstNameKana(): ?string
    {
        return $this->validated('first_name_kana');
    }

    public function getLastNameKana(): ?string
    {
        return $this->validated('last_name_kana');
    }

    public function getPhoneNumber(): string
    {
        return $this->validated('phone');
    }

    public function getPostCode(): ?string
    {
        return $this->validated('post_code');
    }

    public function getPrefecture(): ?int
    {
        return $this->validated('prefecture');
    }

    public function getAddress1(): ?string
    {
        return $this->validated('address1');
    }

    public function getAddress2(): ?string
    {
        return $this->validated('address2');
    }
}
