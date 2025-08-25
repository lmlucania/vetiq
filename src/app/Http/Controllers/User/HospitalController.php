<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Application\Service\User\HospitalInfo\GetHospitalDetailService;
use App\Application\Service\User\HospitalInfo\GetHospitalsService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\HospitalInfo\IndexHospitalRequest;
use App\Transformers\HospitalTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class HospitalController extends Controller
{
    public function __construct(
        private GetHospitalsService $getHospitalsService,
        private GetHospitalDetailService $getHospitalDetailService,
    ) {
    }

    /**
     * @lrd:start
     * 病院の一覧
     * @lrd:end
     */
    public function index(IndexHospitalRequest $request)
    {
        $paginator = $this->getHospitalsService->execute(
            page:$request->getPage(),
            perPage: $request->getPerPage(),
            keyword: $request->getKeyword(),
            tagIds: $request->getTags(),
            prefectureCodes: $request->getPrefectures(),
            addresses: $request->getAddresses(),
            sort: $request->getSort(),
            date: $request->getDate(),
            timeRange: $request->getTimeRangeDto(),
            queryParam: $request->getAllQuery(),
        );

        return fractal($paginator->getCollection(), new HospitalTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->respond();
    }

    /**
     * @lrd:start
     * 病院の詳細
     * @lrd:end
     */
    public function show(int $id)
    {
        $hospital = $this->getHospitalDetailService->execute($id);

        return fractal($hospital, new HospitalTransformer())->respond();
    }
}
