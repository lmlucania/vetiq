<?php

declare(strict_types=1);

namespace App\Application\Service;

use App\Domains\Hospital\Factory\HospitalFactory;
use App\Domains\Menu\Entity\Menu;
use App\Domains\Menu\Factory\MenuFactory;
use App\Domains\Menu\Repository\MenuRepositoryInterface;
use App\Domains\Menu\ValueObjects\MenuUuid;
use App\Exceptions\NotFoundException;
use App\Models\MenuModel;
use Illuminate\Support\Str;

class MenuService
{
    public function __construct(
        private readonly MenuRepositoryInterface $menuRepository,
        private readonly MenuFactory $menuFactory,
        private readonly HospitalFactory $hospitalFactory,
    ) {
    }

    /**
     * ログインスタッフが所属する病院の診察メニューをuuidで取得する
     * @param string $uuid
     * @return Menu
     * @throws NotFoundException
     * @throws \App\Exceptions\InvalidArgumentException
     */
    public function getHospitalOwnByUuid(string $uuid): Menu
    {
        $hospitalEntity = $this->hospitalFactory::createEntityFromAuthStaff();

        $menuModel  = $this->menuRepository->getByUuid(new MenuUuid($uuid));
        $menuEntity = $this->menuFactory->modelToEntity($menuModel);

        if (! $menuEntity->belongsToHospital($hospitalEntity->getId())) {
            throw new NotFoundException();
        }

        return $menuEntity;
    }

    public function store(string $name, string $detail, int $requiredTime, bool $isPublished):bool
    {
        $hospitalEntity = $this->hospitalFactory::createEntityFromAuthStaff();

        $id         = $this->menuRepository->generateId(MenuModel::class);
        $menuEntity = $this->menuFactory->createEntity(
            id:$id,
            uuid:(string)Str::uuid(),
            hospitalId: $hospitalEntity->getId()->getValue(),
            name:$name,
            detail: $detail,
            requiredTime: $requiredTime,
            isPublished: $isPublished,
        );

        return $this->menuRepository->create($menuEntity);
    }

    public function update(string $uuid, string $name, string $detail, int $requiredTime, bool $isPublished):bool
    {
        $menuEntity = $this->getHospitalOwnByUuid($uuid);

        $menuEntity = $menuEntity->update(
            name:$name,
            detail: $detail,
            requiredTime: $requiredTime,
            isPublished: $isPublished,
        );

        return $this->menuRepository->update($menuEntity);
    }

    public function delete(string $uuid):bool
    {
        $menuEntity = $this->getHospitalOwnByUuid($uuid);

        return $this->menuRepository->delete($menuEntity->getId());
    }

    public function publish(string $uuid):bool
    {
        $menuEntity = $this->getHospitalOwnByUuid($uuid);

        $menuEntity = $menuEntity->publish();

        return $this->menuRepository->update($menuEntity);
    }

    public function unpublish(string $uuid):bool
    {
        $menuEntity = $this->getHospitalOwnByUuid($uuid);

        $menuEntity = $menuEntity->unpublish();

        return $this->menuRepository->update($menuEntity);
    }
}
