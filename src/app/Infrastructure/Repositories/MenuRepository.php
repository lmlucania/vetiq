<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Hospital\Factory\HospitalFactory;
use App\Domains\Menu\Entity\Menu;
use App\Domains\Menu\Factory\MenuFactory;
use App\Domains\Menu\Repository\MenuRepositoryInterface;
use App\Domains\Menu\ValueObjects\DeletableMenuId;
use App\Domains\Menu\ValueObjects\MenuId;
use App\Domains\Menu\ValueObjects\MenuUuid;
use App\Exceptions\NotFoundException;
use App\Infrastructure\Repositories\Traits\GenerationId;
use App\Models\MenuModel;

class MenuRepository implements MenuRepositoryInterface
{
    use GenerationId;

    public function __construct(
        private readonly HospitalFactory $hospitalFactory,
        private readonly MenuFactory $menuFactory
    ) {
    }

    public function getByUuid(MenuUuid $uuid): MenuModel
    {
        $menu = MenuModel::firstWhere('uuid', $uuid->getValue());
        if ($menu == null) {
            throw new NotFoundException();
        }

        return $menu;
    }

    public function create(Menu $menuEntity): bool
    {
        $menuModel = $this->menuFactory->entityToModel($menuEntity);
        return $menuModel->save();
    }

    public function update(Menu $menuEntity): bool
    {
        $menuModel = MenuModel::findOrFail($menuEntity->getId()->getValue());

        $menuModel->name          = $menuEntity->getName()->getValue();
        $menuModel->detail        = $menuEntity->getDetail()->getValue();
        $menuModel->required_time = $menuEntity->getRequiredTime()->getValue();
        $menuModel->is_published  = $menuEntity->getIsPublished()->getValue();

        return $menuModel->update();
    }

    public function delete(DeletableMenuId $id): bool
    {
        $menuModel = MenuModel::findOrFail($id->getValue());

        return $menuModel->delete();
    }
}
