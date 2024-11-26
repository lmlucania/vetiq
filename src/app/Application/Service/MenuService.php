<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domains\Hospital\Factory\HospitalFactory;
use App\Domains\Menu\Entity\Menu;
use App\Domains\Menu\Factory\MenuFactory;
use App\Domains\Menu\Repository\MenuRepositoryInterface;
use App\Domains\Menu\ValueObjects\MenuUuid;
use App\Exceptions\NotFoundException;

class MenuService
{
    public function __construct(
        private readonly MenuRepositoryInterface $menuRepository,
        private readonly MenuFactory $menuFactory,
        private readonly HospitalFactory $hospitalFactory,
    ) {
    }

    public function getHospitalOwnByUuid(string $uuid): Menu
    {
        $menuModel      = $this->menuRepository->getByUuid(new MenuUuid($uuid));
        $menuEntity     = $this->menuFactory->modelToEntity($menuModel);
        $hospitalEntity = $this->hospitalFactory::createEntityFromAuthStaff();

        if (! $menuEntity->getHospitalId()->equals($hospitalEntity->getId())) {
            throw new NotFoundException();
        }

        return $menuEntity;
    }
}
