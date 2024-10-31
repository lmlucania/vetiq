<?php

declare(strict_types=1);

namespace App\Domains\Hospital\Repositories;

use App\Domains\Hospital\Entity\Hospital;
use App\Domains\Hospital\ValueObjects\HospitalId;
use App\Domains\Hospital\ValueObjects\PublicId;
use Illuminate\Support\Collection;

interface HospitalRepositoryInterface
{
    public function getById(HospitalId $id): ?Hospital;

    public function getByPublicId(PublicId $publicId): ?Hospital;

    public function getList(): Collection;
}
