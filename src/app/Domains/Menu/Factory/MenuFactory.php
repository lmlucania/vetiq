<?php

declare(strict_types=1);

namespace App\Domains\Menu\Factory;

use App\Application\Dto\MenuDto;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Domains\Menu\Entity\Menu;
use App\Domains\Menu\ValueObjects\Detail;
use App\Domains\Menu\ValueObjects\IsPublished;
use App\Domains\Menu\ValueObjects\MenuId;
use App\Domains\Menu\ValueObjects\MenuUuid;
use App\Domains\Menu\ValueObjects\Name;
use App\Domains\Menu\ValueObjects\RequiredTime;
use App\Models\MenuModel;

class MenuFactory
{
    public function modelToEntity(MenuModel $menuModel): Menu
    {
        return new Menu(
            new MenuId($menuModel->id),
            new MenuUuid($menuModel->uuid),
            new HospitalId($menuModel->hospital_id),
            new Name($menuModel->name),
            new Detail($menuModel->detail),
            new RequiredTime($menuModel->required_time),
            new IsPublished($menuModel->is_published),
        );
    }

    public function entityToModel(Menu $menuEntity): MenuModel
    {
        $menuModel = new MenuModel();

        $menuModel->id            = $menuEntity->getId()->getValue();
        $menuModel->uuid          = $menuEntity->getUuid()->getValue();
        $menuModel->hospital_id   = $menuEntity->getHospitalId()->getValue();
        $menuModel->name          = $menuEntity->getName()->getValue();
        $menuModel->detail        = $menuEntity->getDetail()->getValue();
        $menuModel->required_time = $menuEntity->getRequiredTime()->getValue();
        $menuModel->is_published  = $menuEntity->getIsPublished()->getValue();

        return $menuModel;
    }

    public function entityToDto(Menu $menuEntity): MenuDto
    {
        return new MenuDto(
            $menuEntity->getUuid(),
            $menuEntity->getName(),
            $menuEntity->getDetail(),
            $menuEntity->getRequiredTime(),
            $menuEntity->getIsPublished(),
        );
    }

    public function createEntity(
        int $id,
        string $uuid,
        int $hospitalId,
        string $name,
        string $detail,
        int $requiredTime,
        bool $isPublished
    ): Menu {
        return new Menu(
            new MenuId($id),
            new MenuUuid($uuid),
            new HospitalId($hospitalId),
            new Name($name),
            new Detail($detail),
            new RequiredTime($requiredTime),
            new IsPublished($isPublished),
        );
    }

    public function updateModelFromEntity(
        MenuModel $menuModel,
        Menu $menu
    ):MenuModel {
        $menuModel->name          = $menu->getName()->getValue();
        $menuModel->detail        = $menu->getDetail()->getValue();
        $menuModel->required_time = $menu->getRequiredTime()->getValue();
        $menuModel->is_published  = $menu->getIsPublished()->getValue();

        return $menuModel;
    }
}
