<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\IndexHospitalRequest;
use App\Infrastructure\QueryService\HospitalQueryServiceInterface;
use App\Transformers\HospitalTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class HospitalController extends Controller
{
    public function __construct(
        private HospitalQueryServiceInterface $hospitalQueryService,
    ) {
    }

    /**
     * @lrd:start
     * 病院の一覧
     * @lrd:end
     */
    public function index(IndexHospitalRequest $request)
    {
        $paginator = $this->hospitalQueryService->listByCriteria(
            page:$request->getPage(),
            perPage: $request->getPerPage(),
            keyword: $request->getKeyword(),
            sort: $request->getSort(),
            queryParam: $request->getAllQuery(),
        );

        return fractal($paginator->getCollection(), new HospitalTransformer())
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->respond();
    }
}
