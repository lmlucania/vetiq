<?php

declare(strict_types=1);

namespace App\Domains\Pet\Repository;

use App\Domains\Pet\Enum\Gender;
use App\Models\Pet;
use Carbon\Carbon;
use Illuminate\Support\Collection;

interface PetRepositoryInterface
{
    public function getByUserIdAndId(int $userId, int $id);

    public function getListByUserId(int $userId): Collection;

    public function create(
        int $userId,
        string $name,
        Gender $gender,
        ?Carbon $birthday,
        ?Carbon $startedCareAt,
        ?string $remark,
    ): Pet;

    public function update(
        int $id,
        string $name,
        Gender $gender,
        ?Carbon $birthday,
        ?Carbon $startedCareAt,
        ?string $remark,
        ?string $imagePath,
    ):bool;

    public function delete(int $id):bool;

    public function updateImagePath(int $id, string $path): bool;
}
