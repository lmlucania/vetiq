<?php

declare(strict_types=1);

namespace App\Domains\Menu\Repository;

use App\Models\Menu;

interface MenuRepositoryInterface
{
    public function getByHospitalIdAndId(int $hospitalId, int $id): Menu;

    public function create(string $uuid, int $hospitalId, string $name, string $detail, int $requiredTime, bool $isPublished):Menu;

    public function update(int $id, string $name, string $detail, int $requiredTime, bool $isPublished):bool;

    public function delete(int $id):bool;
}
