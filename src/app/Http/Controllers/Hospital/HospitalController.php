<?php

declare(strict_types=1);

namespace App\Http\Controllers\Hospital;

use App\Application\Dto\Request\HospitalImageDto;
use App\Application\Service\Hospital\HospitalInfo\GetHospitalDetailService;
use App\Application\Service\Hospital\HospitalInfo\UpdateHospitalInfoService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hospital\HospitalInfo\UpdateHospitalRequest;
use App\Transformers\HospitalTransformer;

class HospitalController extends Controller
{
    public function __construct(
        private GetHospitalDetailService $getHospitalDetailService,
        private UpdateHospitalInfoService $updateHospitalInfoService,
    ) {
    }

    /**
     * @lrd:start
     * 病院情報を取得
     * @lrd:end
     */
    public function show()
    {
        $hospital = $this->getHospitalDetailService->execute();
        return fractal($hospital, new HospitalTransformer())->parseIncludes(['images'])->respond();
    }

    /**
     * @lrd:start
     * 病院情報を更新
     * @lrd:end
     */
    public function update(UpdateHospitalRequest $request)
    {
        $dtos = [];
        foreach ($request->getImages() as $index => $image) {
            $dtos[] = new HospitalImageDto(
                id: isset($image['id']) ? (int)$image['id'] : null,
                file: $image['file'] ?? null,
                displayOrder: $index + 1,
            );
        }

        $success = $this->updateHospitalInfoService->execute(
            name: $request->getName(),
            phone: $request->getPhone(),
            postCode: $request->getPostCode(),
            prefecture: $request->getPrefecture(),
            address1: $request->getAddress1(),
            address2: $request->getAddress2(),
            isPublished: $request->isPublished(),
            dtos: $dtos,
        );

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }
}
