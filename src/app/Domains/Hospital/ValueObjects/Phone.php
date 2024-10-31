<?php

declare(strict_types=1);

namespace App\Domains\Hospital\ValueObjects;

use App\Exceptions\InvalidArgumentException;

final class Phone
{
    public function __construct(
        private string $phone
    )
    {
        // 日本の電話番号形式を検証: 「0」から始まる10桁または11桁の数字
        if (! preg_match('/^0\d{9,10}$/', $phone)) {
            throw new InvalidArgumentException("phone");
        }
    }

    public function getValue(): string
    {
        return $this->phone;
    }
}
