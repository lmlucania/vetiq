<?php

declare(strict_types=1);

namespace App\Application\Dto;

use App\Domains\Menu\ValueObjects\Detail;
use App\Domains\Menu\ValueObjects\IsPublished;
use App\Domains\Menu\ValueObjects\MenuUuid;
use App\Domains\Menu\ValueObjects\Name;
use App\Domains\Menu\ValueObjects\RequiredTime;

class MenuDto
{
    public function __construct(
        private MenuUuid $uuid,
        private Name $name,
        private Detail $detail,
        private RequiredTime $requiredTime,
        private IsPublished $isPublished,
    ) {
    }

    public function getUuid(): MenuUuid
    {
        return $this->uuid;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getDetail(): Detail
    {
        return $this->detail;
    }

    public function getRequiredTime(): RequiredTime
    {
        return $this->requiredTime;
    }

    public function getIsPublished(): IsPublished
    {
        return $this->isPublished;
    }
}
