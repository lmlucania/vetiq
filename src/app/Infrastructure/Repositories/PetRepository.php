<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Pet\Enum\Gender;
use App\Domains\Pet\Repository\PetRepositoryInterface;
use App\Exceptions\NotFoundException;
use App\Models\Pet;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class PetRepository implements PetRepositoryInterface
{
    public function getByUuid(string $uuid): Pet
    {
        $model = Pet::firstWhere('uuid', $uuid);
        if ($model == null) {
            throw new NotFoundException();
        }

        return $model;
    }

    public function getListByUserId(int $userId): Collection
    {
        return Pet::where('user_id', $userId)
            ->orderBy('id')
            ->get();
    }

    public function create(
        int $userId,
        string $name,
        Gender $gender,
        ?Carbon $birthday,
        ?Carbon $startedCareAt,
        ?string $remark,
    ): bool {
        return Pet::create([
            'uuid'          => (string)Str::uuid(),
            'name'          => $name,
            'gender'        => $gender,
            'birthday'      => $birthday,
            'startedCareAt' => $startedCareAt,
            'remark'        => $remark,
        ]);
    }

    public function update(
        int $id,
        string $name,
        Gender $gender,
        ?Carbon $birthday,
        ?Carbon $startedCareAt,
        ?string $remark,
    ): bool {
        $model = Pet::firstOrFail($id);

        $model->name            = $name;
        $model->gender          = $gender;
        $model->birthday        = $birthday;
        $model->started_care_at = $startedCareAt;
        $model->remark          = $remark;

        return $model->save();
    }

    public function delete(int $id): bool
    {
        $model = Pet::firstOrFail($id);

        $model->delete();
    }
}
