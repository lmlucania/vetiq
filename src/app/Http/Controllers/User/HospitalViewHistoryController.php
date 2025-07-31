<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Application\Service\User\HospitalViewHistory\DeleteHospitalViewHistoryService;
use App\Http\Controllers\Controller;

class HospitalViewHistoryController extends Controller
{
    public function __construct(
        private DeleteHospitalViewHistoryService $deleteHospitalViewHistoryService,
    ) {
    }

    /**
     * 病院の閲覧履歴の一覧
     */
    public function index()
    {
        //
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
