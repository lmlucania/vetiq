<?php

declare(strict_types=1);

namespace App\Domains\Menu\Repository;

use App\Domains\Menu\Entity\Menu;
use App\Domains\Menu\ValueObjects\DeletableMenuId;
use App\Domains\Menu\ValueObjects\MenuUuid;
use App\Models\MenuModel;

interface MenuRepositoryInterface
{
    public function generateId(string $modelClass): int;

    public function getByUuid(MenuUuid $uuid): MenuModel;

    public function create(Menu $menuEntity):bool;

    public function update(Menu $menuEntity):bool;

    public function delete(DeletableMenuId $id):bool;
}
