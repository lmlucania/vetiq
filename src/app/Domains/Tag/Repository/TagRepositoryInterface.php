<?php

namespace App\Domains\Tag\Repository;

use Illuminate\Support\Collection;

interface TagRepositoryInterface
{
    public function getManyByIds(array $ids): Collection;
}
