<?php

declare(strict_types=1);

namespace App\Domains\Menu\ValueObjects;

final class DeletableMenuId
{
    private function __construct(
        private int $id
    ) {
    }

    /**
     * @param MenuId $menuId
     * @return self
     */
    public static function fromMenuId(MenuId $menuId):self
    {
        return new self($menuId->getValue());
    }

    public function getValue(): int
    {
        return $this->id;
    }
}
