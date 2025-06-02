<?php

declare(strict_types=1);

namespace App\Application\Dto\Response;

use App\Exceptions\InvalidArgumentException;
use Illuminate\Support\Collection;

class ExceptionHourCollectionDto
{
    /** @var ExceptionHourDto[] */
    private Collection $dtoCollection;

    public function __construct(Collection $dtoCollection)
    {
        foreach ($dtoCollection as $dto) {
            if (! $dto instanceof ExceptionHourDto) {
                throw new InvalidArgumentException('例外受付時間のデータが不正です。');
            }
        }

        $this->dtoCollection = $dtoCollection;
    }

    /**
     * @return ExceptionHourDto[]
     */
    public function getCollection(): Collection
    {
        return $this->dtoCollection;
    }
}
