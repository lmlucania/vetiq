<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Pet\Enum\Gender;
use App\Domains\Pet\Repository\PetRepositoryInterface;
use App\Models\Pet;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class PetRepository implements PetRepositoryInterface
{
    public function getByUserIdAndId(int $userId, int $id): Pet
    {
        return Pet::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();
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
    ): Pet {
        return Pet::create([
            'user_id'         => $userId,
            'name'            => $name,
            'gender'          => $gender,
            'birthday'        => $birthday,
            'started_care_at' => $startedCareAt,
            'remark'          => $remark,
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
        $model = Pet::findOrFail($id);

        $model->name            = $name;
        $model->gender          = $gender;
        $model->birthday        = $birthday;
        $model->started_care_at = $startedCareAt;
        $model->remark          = $remark;

        return $model->save();
    }

    public function delete(int $id): bool
    {
        return Pet::findOrFail($id)->delete();
    }
}
