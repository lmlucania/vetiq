<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Hospital\Entity\Hospital;
use App\Domains\Hospital\Factory\HospitalFactory;
use App\Domains\Menu\Repository\MenuRepositoryInterface;
use App\Domains\Menu\ValueObjects\MenuId;
use App\Domains\Menu\ValueObjects\MenuUuid;
use App\Infrastructure\Repositories\Traits\GenerationId;
use App\Models\HospitalModel;
use App\Models\MenuModel;
use Illuminate\Support\Collection;

class MenuRepository implements MenuRepositoryInterface
{
    use GenerationId;

    public function __construct(
        private readonly HospitalFactory $hospitalFactory
    ) {
    }

    public function getById(MenuId $id): MenuModel
    {
        return MenuModel::findOrFail($id->getValue());
    }

    public function getByUuid(MenuUuid $uuid): MenuModel
    {
        return MenuModel::firstWhere('uuid', $uuid->getValue());
    }

    public function getList(): Collection
    {
        return HospitalModel::all();
    }

    public function create(Hospital $hospitalEntity): bool
    {
        $hospitalModel = $this->hospitalFactory->entityToModel($hospitalEntity);
        return $hospitalModel->save();
    }

    public function update(Hospital $hospitalEntity): bool
    {
        $hospitalModel = HospitalModel::findOrFail($hospitalEntity->getId()->getValue());
        $hospitalModel = $this->hospitalFactory->updateModelFromEntity($hospitalModel, $hospitalEntity);
        return $hospitalModel->update();
    }
}
