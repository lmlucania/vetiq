<?php

declare(strict_types=1);

namespace App\Domains\Menu\Entity;

use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Domains\Menu\ValueObjects\Detail;
use App\Domains\Menu\ValueObjects\IsPublished;
use App\Domains\Menu\ValueObjects\MenuId;
use App\Domains\Menu\ValueObjects\MenuUuid;
use App\Domains\Menu\ValueObjects\Name;
use App\Domains\Menu\ValueObjects\RequiredTime;

class Menu
{
    public function __construct(
        private MenuId $menuId,
        private MenuUuid $menuUuid,
        private HospitalId $hospitalId,
        private Name $name,
        private Detail $detail,
        private RequiredTime $requiredTime,
        private IsPublished $isPublished,
    ) {
    }

    public function getId(): MenuId
    {
        return $this->menuId;
    }

    public function getUuid(): MenuUuid
    {
        return $this->menuUuid;
    }

    public function getName(): Name
    {
        return $this->name;
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

    /**
     * 診察メニューが病院に属しているか
     * @param HospitalId $hospitalId
     * @return bool
     */
    public function belongsToHospital(HospitalId $hospitalId): bool
    {
        return $this->hospitalId == $hospitalId;
    }

    public function update(string $name, string $detail, int $requiredTime, bool $isPublished): Menu
    {
        return new Menu(
            $this->menuId,
            $this->menuUuid,
            $this->hospitalId,
            new Name($name),
            new Detail($detail),
            new RequiredTime($requiredTime),
            new IsPublished($isPublished),
        );
    }

    public function publish():Menu
    {
        return new Menu(
            $this->menuId,
            $this->menuUuid,
            $this->hospitalId,
            $this->name,
            $this->detail,
            $this->requiredTime,
            new IsPublished(true),
        );
    }

    public function unpublish():Menu
    {
        return new Menu(
            $this->menuId,
            $this->menuUuid,
            $this->hospitalId,
            $this->name,
            $this->detail,
            $this->requiredTime,
            new IsPublished(false),
        );
    }
}
