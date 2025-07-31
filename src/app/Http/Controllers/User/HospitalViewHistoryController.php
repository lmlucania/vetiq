<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Application\Service\User\HospitalViewHistory\DeleteHospitalViewHistoryService;
use App\Application\Service\User\HospitalViewHistory\GetMyHospitalViewHistoriesService;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\HospitalViewHistory\IndexHospitalViewHistoryRequest;
use App\Transformers\HospitalViewHistoryTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class HospitalViewHistoryController extends Controller
{
    public function __construct(
        private GetMyHospitalViewHistoriesService $getMyHospitalViewHistoriesService,
        private DeleteHospitalViewHistoryService $deleteHospitalViewHistoryService,
    ) {
    }

    /**
     * 病院の閲覧履歴の一覧
     */
    public function index(IndexHospitalViewHistoryRequest $request)
    {
        $paginator = $this->getMyHospitalViewHistoriesService->execute(
            page:$request->getPage(),
            perPage: $request->getPerPage(),
            sort: $request->getSort(),
            queryParam: $request->getAllQuery(),
        );

        return fractal($paginator->getCollection(), new HospitalViewHistoryTransformer())
            ->parseIncludes(['hospital'])
            ->paginateWith(new IlluminatePaginatorAdapter($paginator))
            ->respond();
    }

    /**
     * 病院の閲覧履歴を削除
     */
    public function destroy(int $hospitalId)
    {
        $success = $this->deleteHospitalViewHistoryService->execute($hospitalId);

        if ($success) {
            return response()->success();
        }
        return response()->error();
    }
}
