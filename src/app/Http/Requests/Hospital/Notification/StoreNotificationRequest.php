<?php

declare(strict_types=1);

namespace App\Http\Requests\Hospital\Notification;

use App\Http\Requests\Base\ApiRequest;
use Carbon\Carbon;

class StoreNotificationRequest extends ApiRequest
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
            'title'        => 'required|string',
            'detail'       => 'required|string',
            'is_published' => 'required|boolean',
            'published_at' => 'required|date_format:Y-m-d H:i',
        ];
    }

    public function getTitle(): string
    {
        return $this->validated('title');
    }

    public function getDetail(): string
    {
        return $this->validated('detail');
    }

    public function getIsPublished(): bool
    {
        return $this->validated('is_published');
    }

    public function getPublishedAt(): Carbon
    {
        return Carbon::createFromFormat('Y-m-d H:i', $this->validated('published_at'));
    }
}
