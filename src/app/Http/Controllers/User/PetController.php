<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Application\Service\User\Pet\CreatePetService;
use App\Application\Service\User\Pet\DeletePetService;
use App\Application\Service\User\Pet\GetMyPetsService;
use App\Application\Service\User\Pet\GetPetDetailService;
use App\Application\Service\User\Pet\UpdatePetService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\Pet\StorePetRequest;
use App\Http\Requests\User\Pet\UpdatePetRequest;
use App\Transformers\PetTransformer;

class PetController extends Controller
{
    public function __construct(
        private GetMyPetsService $getMyPetsService,
        private CreatePetService $createPetService,
        private GetPetDetailService $getPetDetailService,
        private UpdatePetService $updatePetService,
        private DeletePetService $deletePetService,
    ) {
    }

    /**
     * @lrd:start
     * ユーザーのペット一覧
     * @lrd:end
     */
    public function index()
    {
        $petCollection = $this->getMyPetsService->execute();

        return fractal($petCollection, new PetTransformer())->respond();
    }

    /**
     * @lrd:start
     * ペットの登録
     * @lrd:end
     */
    public function store(StorePetRequest $request)
    {
        $success = $this->createPetService->execute(
            name: $request->getName(),
            gender: $request->getGender(),
            birthday: $request->getBirthday(),
            startedCareAt: $request->getStartedCareAt(),
            remark: $request->getRemark(),
            image: $request->getImage(),
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
    public function show(int $id)
    {
        $pet = $this->getPetDetailService->execute($id);

        return fractal($pet, new PetTransformer())->respond();
    }

    /**
     * @lrd:start
     * ペットの更新
     * @lrd:end
     */
    public function update(UpdatePetRequest $request, int $id)
    {
        $success = $this->updatePetService->execute(
            id: $id,
            name: $request->getName(),
            gender: $request->getGender(),
            birthday: $request->getBirthday(),
            startedCareAt: $request->getStartedCareAt(),
            remark: $request->getRemark(),
            image: $request->getImage(),
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
    public function destroy(int $id)
    {
        $success = $this->deletePetService->execute($id);

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }
}
