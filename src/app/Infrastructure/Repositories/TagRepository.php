<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domains\Tag\Repository\TagRepositoryInterface;
use App\Models\Tag;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TagRepository implements TagRepositoryInterface
{
    public function getManyByIds(array $ids): Collection
    {
        return Tag::whereIn('id', $ids)->get();
    }
}
