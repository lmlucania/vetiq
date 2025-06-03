<?php

declare(strict_types=1);

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use stdClass;

class UserProfileTransformer extends TransformerAbstract
{
    public function transform(stdClass $userProfile)
    {
        return [
            'email'        => $userProfile->email,
            'first_name'   => $userProfile->first_name,
            'last_name'    => $userProfile->last_name,
            'phone_number' => $userProfile->phone_number,
            'post_code'    => $userProfile->post_code,
            'prefecture'   => $userProfile->prefecture,
            'address1'     => $userProfile->address1,
            'address2'     => $userProfile->address2,
        ];
    }
}
