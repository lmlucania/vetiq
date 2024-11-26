<?php

declare(strict_types=1);

namespace App\Domains\Menu\Repository;

use App\Domains\Menu\Entity\Menu;
use App\Domains\Menu\ValueObjects\MenuId;
use App\Domains\Menu\ValueObjects\MenuUuid;
use App\Models\MenuModel;
use Illuminate\Support\Collection;

interface MenuRepositoryInterface
{
    public function generateId(string $modelClass): int;

    public function getById(MenuId $id): MenuModel;

    public function getByUuid(MenuUuid $uuid): MenuModel;

    //    public function getList(): Collection;
    //
    //    public function create(Menu $menuEntity):bool;
    //
    //    public function update(Menu $menuEntity):bool;
}
