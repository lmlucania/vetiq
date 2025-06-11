<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Models\Hospital;
use League\Fractal\TransformerAbstract;

class HospitalTransformer extends TransformerAbstract
{
    public function transform(Hospital $hospital)
    {
        return [
            'uuid'         => $hospital->uuid,
            'name'         => $hospital->name,
            'phone'        => $hospital->phone,
            'post_code'    => $hospital->post_code,
            'prefecture'   => $hospital->prefecture,
            'address1'     => $hospital->address1,
            'address2'     => $hospital->address2,
            'is_published' => $hospital->is_published,
        ];
    }
}
