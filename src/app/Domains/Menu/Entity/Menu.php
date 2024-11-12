<?php

declare(strict_types=1);

namespace App\Domains\Menu\Entity;

use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Domains\Menu\ValueObjects\Detail;
use App\Domains\Menu\ValueObjects\IsPublished;
use App\Domains\Menu\ValueObjects\MenuId;
use App\Domains\Menu\ValueObjects\RequiredTime;

class Menu
{
    public function __construct(
        private MenuId $menuId,
        private HospitalId $hospitalId,
        private Detail $detail,
        private RequiredTime $requiredTime,
        private IsPublished $isPublished,
    ) {
    }

    public function getMenuId(): MenuId
    {
        return $this->menuId;
    }

    public function getHospitalId(): HospitalId
    {
        return $this->hospitalId;
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
