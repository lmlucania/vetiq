<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\HospitalViewHistory;
use App\Transformers\Traits\FractalTransformable;
use League\Fractal\TransformerAbstract;

class HospitalViewHistoryTransformer extends TransformerAbstract
{
    use FractalTransformable;

    protected array $availableIncludes = ['hospital'];

    public function transform(HospitalViewHistory $hospitalViewHistory)
    {
        return [
            'updated_at' => $hospitalViewHistory->updated_at,
        ];
    }

    public function includeHospital(HospitalViewHistory $hospitalViewHistory)
    {
        $hospital = $hospitalViewHistory->hospital;

        return $this->primitive(
            $hospital,
            fn ($hospital) => (new HospitalTransformer())->transform($hospital),
        );
    }
}
