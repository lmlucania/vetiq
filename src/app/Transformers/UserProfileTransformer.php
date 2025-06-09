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
            'email'           => $userProfile->email,
            'first_name'      => $userProfile->first_name,
            'last_name'       => $userProfile->last_name,
            'first_name_kana' => $userProfile->first_name_kana,
            'last_name_kana'  => $userProfile->last_name_kana,
            'phone'           => $userProfile->phone,
            'post_code'       => $userProfile->post_code,
            'prefecture'      => $userProfile->prefecture,
            'address1'        => $userProfile->address1,
            'address2'        => $userProfile->address2,
        ];
    }
}
