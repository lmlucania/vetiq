<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Menu\Repository\MenuRepositoryInterface;
use App\Models\Menu;

class MenuRepository implements MenuRepositoryInterface
{
    public function getByHospitalIdAndId(int $hospitalId, int $id): Menu
    {
        return Menu::where('hospital_id', $hospitalId)->findOrFail($id);
    }

    // fixme ここから先を直す
    public function create(
        int $hospitalId,
        string $name,
        string $detail,
        int $requiredTime,
        bool $isPublished
    ): Menu {
        return Menu::create([
            'hospital_id'   => $hospitalId,
            'name'          => $name,
            'detail'        => $detail,
            'required_time' => $requiredTime,
            'is_published'  => $isPublished,
        ]);
    }

    public function update(
        int $id,
        string $name,
        string $detail,
        int $requiredTime,
        bool $isPublished
    ): bool {
        $menu                = Menu::findOrFail($id);
        $menu->name          = $name;
        $menu->detail        = $detail;
        $menu->required_time = $requiredTime;
        $menu->is_published  = $isPublished;

        return $menu->save();
    }

    public function delete(int $id): bool
    {
        $menuModel = Menu::findOrFail($id->getValue());

        return $menuModel->delete();
    }
}
