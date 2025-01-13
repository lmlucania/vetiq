<?php

declare(strict_types=1);

namespace App\Domains\Vet\Repository;

use App\Domains\Vet\Entity\Vet;
use App\Domains\Vet\ValueObjects\VetId;
use App\Domains\Vet\ValueObjects\VetUuid;
use App\Models\VetModel;

interface VetRepositoryInterface
{
    public function generateId(string $modelClass): int;

    public function getByUuid(VetUuid $uuid): VetModel;

    public function create(Vet $vetEntity):bool;

    public function update(Vet $vetEntity):bool;

    public function delete(VetId $id):bool;
}
