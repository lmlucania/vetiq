<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Application\Service\PetService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StorePetRequest;
use App\Transformers\PetTransformer;
use Illuminate\Http\Request;

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
     * ペットの登録
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
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $pet = $this->petService->getByUuid($uuid);

        return fractal($pet, new PetTransformer())->respond();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
