<?php

declare(strict_types=1);

namespace App\Http\Requests\User;

use App\Domains\Pet\Enum\Gender;
use App\Http\Requests\Base\ApiRequest;
use Illuminate\Validation\Rules\Enum;

class UpdatePetRequest extends ApiRequest
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
            'name'            => ['required', 'string', 'max:255'],
            'gender'          => ['required', 'integer', new Enum(Gender::class)],
            'birthday'        => ['nullable', 'date'],
            'started_care_at' => ['nullable', 'date'],
            'remark'          => ['nullable', 'string'],
        ];
    }

    public function getName()
    {
        return $this->validated('name');
    }

    public function getGender(): int
    {
        return $this->validated('gender');
    }

    public function getBirthday(): ?string
    {
        return $this->validated('birthday');
    }

    public function getStartedCareAt(): ?string
    {
        return $this->validated('started_care_at');
    }

    public function getRemark(): ?string
    {
        return $this->validated('remark');
    }
}
