<?php

declare(strict_types=1);

namespace App\Application\Dto\Response;

use App\Exceptions\InvalidArgumentException;
use Illuminate\Support\Collection;

class BusinessHourCollectionDto
{
    /** @var BusinessHourDto[] */
    private Collection $dtoCollection;

    public function __construct(Collection $dtoCollection)
    {
        foreach ($dtoCollection as $dto) {
            if (! $dto instanceof BusinessHourDto) {
                throw new InvalidArgumentException('営業時間のデータが不正です。');
            }
        }

        $this->dtoCollection = $dtoCollection;
    }

    /**
     * @return BusinessHourDto[]
     */
    public function getCollection(): Collection
    {
        return $this->dtoCollection;
    }
}
