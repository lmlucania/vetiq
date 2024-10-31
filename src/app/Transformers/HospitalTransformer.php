<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Application\Dto\HospitalDto;
use League\Fractal\TransformerAbstract;

class HospitalTransformer extends TransformerAbstract
{
    public function transform(HospitalDto $hospitalDto)
    {
        return [
            'id'        => $hospitalDto->getPublicId()->getValue(),
            'name'      => $hospitalDto->getName()->getValue(),
            'zipcode'   => $hospitalDto->getZipcode()->getValue(),
            'address'   => $hospitalDto->getAddress()->getValue(),
            'phone'     => $hospitalDto->getPhone()->getValue(),
            'is_public' => $hospitalDto->getIsPublished()->getValue(),
        ];
    }
}
