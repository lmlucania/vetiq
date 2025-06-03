<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Pet;
use League\Fractal\TransformerAbstract;

class PetTransformer extends TransformerAbstract
{
    public function transform(Pet $pet)
    {
        return [
            'uuid'            => $pet->uuid,
            'name'            => $pet->name,
            'gender'          => $pet->gender,
            'birthday'        => $pet->birthday?->format('Y-m-d'),
            'started_care_at' => $pet->started_care_at?->format('Y-m-d'),
            'remark'          => $pet->remark,
        ];
    }
}
