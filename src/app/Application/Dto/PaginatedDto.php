<?php

declare(strict_types=1);

namespace App\Application\Dto;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class PaginatedDto
{
    public function __construct(
        private Collection $collection,
        private ?LengthAwarePaginator $paginate,
    ) {
    }

    public function getCollection(): Collection
    {
        return $this->collection;
    }

    public function getPaginate(): ?LengthAwarePaginator
    {
        return $this->paginate;
    }
}
