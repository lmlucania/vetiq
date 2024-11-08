<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Hospital\Entity\Hospital;
use App\Domains\Hospital\Factory\HospitalFactory;
use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Domains\Hospital\ValueObjects\HospitalUuid;
use App\Infrastructure\Repositories\Traits\GenerationId;
use App\Models\HospitalModel;
use Illuminate\Support\Collection;

class HospitalRepository implements HospitalRepositoryInterface
{
    use GenerationId;

    public function __construct(
        private readonly HospitalFactory $hospitalFactory
    ) {
    }

    public function getById(HospitalId $id): HospitalModel
    {
        return HospitalModel::findOrFail($id->getValue());
    }

    public function getByPublicId(HospitalUuid $uuid): ?Hospital
    {
        $hospitalModel = HospitalModel::firstWhere('public_id', $uuid->getValue());
        return $hospitalModel ? $this->hospitalFactory->toEntity($hospitalModel) : null;
    }

    public function getList(): Collection
    {
        return HospitalModel::all();
    }

    public function create(Hospital $hospital): void
    {
        $hospitalModel = $this->hospitalFactory->toModel($hospital);
        $hospitalModel->save();
    }

    public function update(Hospital $hospital): void
    {
        $hospitalModel = HospitalModel::findOrFail($hospital->getId()->getValue());
        $hospitalModel = $this->hospitalFactory->updateModel($hospitalModel, $hospital);
        $hospitalModel->update();
    }
}
