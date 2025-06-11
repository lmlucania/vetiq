<?php

declare(strict_types=1);

namespace App\Application\QueryService;

use Illuminate\Support\Facades\DB;
use stdClass;

class UserQueryService
{
    public function getById(int $id): ?stdClass
    {
        return DB::table('users')
            ->leftJoin('user_profiles', 'users.id', '=', 'user_profiles.user_id')
            ->select(
                'users.email',
                'user_profiles.first_name',
                'user_profiles.last_name',
                'user_profiles.first_name_kana',
                'user_profiles.last_name_kana',
                'user_profiles.phone',
                'user_profiles.post_code',
                'user_profiles.prefecture',
                'user_profiles.address1',
                'user_profiles.address2',
            )
            ->where('users.id', $id)
            ->first();
    }
}
