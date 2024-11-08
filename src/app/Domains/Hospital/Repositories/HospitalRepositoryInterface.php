<?php

declare(strict_types=1);

namespace App\Domains\Hospital\Repositories;

use App\Domains\Hospital\Entity\Hospital;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Domains\Hospital\ValueObjects\HospitalUuid;
use App\Models\HospitalModel;
use Illuminate\Support\Collection;

interface HospitalRepositoryInterface
{
    public function generateId(string $modelClass): int;

    public function getById(HospitalId $id): HospitalModel;

    public function getByPublicId(HospitalUuid $publicId): ?Hospital;

    public function getList(): Collection;

    public function create(Hospital $hospital):void;

    public function update(Hospital $hospital):void;
}
