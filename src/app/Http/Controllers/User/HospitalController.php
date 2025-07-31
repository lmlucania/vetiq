<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Application\Service\User\HospitalInfo\GetHospitalsService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\HospitalInfo\IndexHospitalRequest;
use App\Transformers\HospitalTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class HospitalController extends Controller
{
    public function __construct(
        private GetHospitalsService $getHospitalsService,
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
            sort: $request->getSort(),
            queryParam: $request->getAllQuery(),
        );

        return fractal($paginator->getCollection(), new HospitalTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->respond();
    }
}
