<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Hospital\Entity\Hospital;
use App\Domains\Hospital\Factory\HospitalFactory;
use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;
use App\Domains\Hospital\ValueObjects\HospitalId;
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

        $hospitalModel->name         = $hospitalEntity->getName()->getValue();
        $hospitalModel->zipcode      = $hospitalEntity->getZipcode()->getValue();
        $hospitalModel->address      = $hospitalEntity->getAddress()->getValue();
        $hospitalModel->phone        = $hospitalEntity->getPhone()->getValue();
        $hospitalModel->is_published = $hospitalEntity->getIsPublished()->getValue();

        return $hospitalModel->update();
    }
}
