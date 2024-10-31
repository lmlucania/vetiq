<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Hospital\Entity\Hospital;
use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Domains\Hospital\ValueObjects\PublicId;
use App\Models\HospitalModel;
use Illuminate\Support\Collection;

class HospitalRepository implements HospitalRepositoryInterface
{

    public function getById(HospitalId $id): ?Hospital
    {
        $hospital = HospitalModel::find($id->getValue());
        return $hospital?->toEntity();
    }

    public function getByPublicId(PublicId $publicId): ?Hospital
    {
        $hospital = HospitalModel::firstWhere('public_id', $publicId->getValue());
        return $hospital?->toEntity();
    }

    public function getList(): Collection
    {
        return HospitalModel::all();
    }
}
