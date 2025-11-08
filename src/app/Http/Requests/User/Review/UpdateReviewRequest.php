<?php

declare(strict_types=1);

namespace App\Http\Requests\User\Review;

use App\Domains\Review\Enum\Rating;
use App\Http\Requests\Base\ApiRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateReviewRequest extends ApiRequest
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
            'rating'      => ['required', 'integer', new Enum(Rating::class)],
            'title'       => ['required', 'string', 'max:255'],
            'body'        => ['nullable', 'string'],
            'images.*'    => ['required', 'array'],
            'images.*.id' => [
                'nullable',
                'integer',
                'prohibits:images.*.file',
            ],
            'images.*.file' => [
                'nullable',
                'image',
                'max:5120',
                'prohibits:images.*.id',
            ],
        ];
    }

    public function getRating(): Rating
    {
        return Rating::from((int)$this->validated('rating'));
    }

    public function getTitle(): string
    {
        return $this->validated('title');
    }

    public function getBody(): ?string
    {
        return $this->validated('body');
    }

    /**
     * @return \Illuminate\Http\UploadedFile[]
     */
    public function getImages(): array
    {
        // validated() だと null が返ることがあるので file() が安全
        return $this->validated('images') ?? [];
    }
}
