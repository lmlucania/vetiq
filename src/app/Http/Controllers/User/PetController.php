<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Application\Service\PetService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StorePetRequest;
use App\Http\Requests\User\UpdatePetRequest;
use App\Transformers\PetTransformer;

class PetController extends Controller
{
    public function __construct(
        private PetService $petService,
    ) {
    }

    /**
     * @lrd:start
     * ユーザーのペット一覧
     * @lrd:end
     */
    public function index()
    {
        $petCollection = $this->petService->getMyPets();

        return fractal($petCollection, new PetTransformer())->respond();
    }

    /**
     * @lrd:start
     * ペットの登録
     * @lrd:end
     */
    public function store(StorePetRequest $request)
    {
        $success = $this->petService->create(
            name: $request->getName(),
            gender: $request->getGender(),
            birthday: $request->getBirthday(),
            startedCareAt: $request->getStartedCareAt(),
            remark: $request->getRemark(),
        );

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * @lrd:start
     * ペットの詳細
     * @lrd:end
     */
    public function show(string $uuid)
    {
        $pet = $this->petService->getByUuid($uuid);

        return fractal($pet, new PetTransformer())->respond();
    }

    /**
     * @lrd:start
     * ペットの更新
     * @lrd:end
     */
    public function update(UpdatePetRequest $request, string $uuid)
    {
        $success = $this->petService->update(
            uuid: $uuid,
            name: $request->getName(),
            gender: $request->getGender(),
            birthday: $request->getBirthday(),
            startedCareAt: $request->getStartedCareAt(),
            remark: $request->getRemark(),
        );

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }

    /**
     * @lrd:start
     * ペットの削除
     * @lrd:end
     */
    public function destroy(string $uuid): bool
    {
        $success = $this->petService->delete(
            uuid: $uuid,
        );

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }
}
