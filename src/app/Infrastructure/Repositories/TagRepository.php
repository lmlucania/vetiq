<?php

namespace App\Infrastructure\Repositories;

use App\Domains\Tag\Repository\TagRepositoryInterface;
use App\Models\Tag;
use Illuminate\Support\Collection;

class TagRepository implements TagRepositoryInterface
{

    public function getManyByIds(array $ids): Collection
    {
        return Tag::whereIn('id', $ids)->get();
    }
}
