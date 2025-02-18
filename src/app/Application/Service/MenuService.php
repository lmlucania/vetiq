<?php

declare(strict_types=1);

namespace App\Application\Service;

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
        private readonly AuthStaffService $authStaffService,
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
        $hospitalId = $this->authStaffService->getHospitalId();

        $menuModel  = $this->menuRepository->getByUuid(new MenuUuid($uuid));
        $menuEntity = $this->menuFactory->modelToEntity($menuModel);

        if (! $menuEntity->belongsToHospital($hospitalId)) {
            throw new NotFoundException();
        }

        return $menuEntity;
    }

    public function store(string $name, string $detail, int $requiredTime, bool $isPublished):bool
    {
        $hospitalId = $this->authStaffService->getHospitalId();

        $id         = $this->menuRepository->generateId(MenuModel::class);
        $menuEntity = $this->menuFactory->createEntityFromPrimitive(
            id:$id,
            uuid:(string)Str::uuid(),
            hospitalId: $hospitalId->getValue(),
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
        $deletableId = $menuEntity->getDeletableId();

        return $this->menuRepository->delete($deletableId);
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
