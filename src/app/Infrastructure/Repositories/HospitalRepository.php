<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Hospital\Entity\Hospital;
use App\Domains\Hospital\Factory\HospitalFactory;
use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Exceptions\NotFoundException;
use App\Infrastructure\Repositories\Traits\GenerationId;
use App\Models\Hospital;
use App\Models\VetModel;
use Illuminate\Support\Collection;

class HospitalRepository implements HospitalRepositoryInterface
{
    use GenerationId;

    public function __construct(
        private readonly HospitalFactory $hospitalFactory
    ) {
    }

    public function getById(HospitalId $id): Hospital
    {
        return Hospital::findOrFail($id->getValue());
    }

    public function getByUuid(string $uuid): Hospital
    {
        $hospital = Hospital::firstWhere('uuid', $uuid);
        if ($hospital == null) {
            throw new NotFoundException();
        }

        return $hospital;
    }

    public function getList(): Collection
    {
        return Hospital::all();
    }

    public function create(Hospital $hospitalEntity): bool
    {
        $hospitalModel = $this->hospitalFactory->entityToModel($hospitalEntity);
        return $hospitalModel->save();
    }

    public function update(Hospital $hospitalEntity): bool
    {
        $hospitalModel = Hospital::findOrFail($hospitalEntity->getId()->getValue());

        $hospitalModel->name         = $hospitalEntity->getName()->getValue();
        $hospitalModel->zipcode      = $hospitalEntity->getZipcode()->getValue();
        $hospitalModel->address      = $hospitalEntity->getAddress()->getValue();
        $hospitalModel->phone        = $hospitalEntity->getPhone()->getValue();
        $hospitalModel->is_published = $hospitalEntity->getIsPublished()->getValue();

        return $hospitalModel->update();
    }

    /**
     * 指定された病院に所属する獣医師の数を取得する
     * @param HospitalId $id
     * @return int
     */
    public function countVet(HospitalId $id): int
    {
        return VetModel::where('hospital_id', $id->getValue())->count();
    }
}
