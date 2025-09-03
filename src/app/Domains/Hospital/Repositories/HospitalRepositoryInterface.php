<?php

declare(strict_types=1);

namespace App\Domains\Hospital\Repositories;

use App\Domains\Location\Enum\Prefecture;
use App\Models\Hospital;

interface HospitalRepositoryInterface
{
    public function getById(int $id): Hospital;

    public function update(
        int $id,
        string $name,
        string $phone,
        string $postCode,
        Prefecture $prefecture,
        string $address1,
        ?string $address2,
        bool $isPublished,
    ): bool;
}
