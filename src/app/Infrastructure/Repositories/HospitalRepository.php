<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Hospital\Repositories\HospitalRepositoryInterface;
use App\Domains\Location\Enum\Prefecture;
use App\Models\Hospital;

class HospitalRepository implements HospitalRepositoryInterface
{
    public function getById(int $id): Hospital
    {
        return Hospital::findOrFail($id);
    }

    public function update(
        int $id,
        string $name,
        string $phone,
        string $postCode,
        Prefecture $prefecture,
        string $address1,
        ?string $address2,
        bool $isPublished,
    ): bool {
        $hospital = Hospital::findOrFail($id);

        $hospital->name         = $name;
        $hospital->phone        = $phone;
        $hospital->post_code    = $postCode;
        $hospital->prefecture   = $prefecture;
        $hospital->address1     = $address1;
        $hospital->address2     = $address2;
        $hospital->is_published = $isPublished;

        return $hospital->save();
    }
}
