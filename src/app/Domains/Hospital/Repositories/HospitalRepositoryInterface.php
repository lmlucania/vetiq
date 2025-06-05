<?php

declare(strict_types=1);

namespace App\Domains\Hospital\Repositories;

use App\Domains\Hospital\Entity\Hospital;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Models\HospitalModel;
use Illuminate\Support\Collection;

interface HospitalRepositoryInterface
{
    public function generateId(string $modelClass): int;

    public function getById(HospitalId $id): HospitalModel;

    public function getByUuid(string $uuid): HospitalModel;

    public function getList(): Collection;

    public function create(Hospital $hospitalEntity):bool;

    public function update(Hospital $hospitalEntity):bool;

    public function countVet(HospitalId $id): int;
}
