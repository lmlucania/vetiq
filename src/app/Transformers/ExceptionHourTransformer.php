<?php

declare(strict_types=1);

namespace App\Transformers;

use App\Application\Dto\Response\ExceptionHourDto;
use League\Fractal\TransformerAbstract;

class ExceptionHourTransformer extends TransformerAbstract
{
    public function transform(ExceptionHourDto $dto)
    {
        return [
            'uuid'       => $dto->getExceptionHourUuid()->getValue(),
            'date'       => $dto->getDate()->getValue(),
            'start_time' => $dto->getStartTime()?->getValue()->format('H:i'),
            'end_time'   => $dto->getEndTime()?->getValue()->format('H:i'),
            'is_close'   => $dto->getIsClosed()->getValue(),
            'reason'     => $dto->getReason()?->getValue(),
        ];
    }
}
