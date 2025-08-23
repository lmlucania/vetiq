<?php

declare(strict_types=1);

namespace App\Application\Service\Hospital\ExceptionHour;

use App\Application\Dto\Request\BusinessHourDto;
use App\Application\Dto\Request\ExceptionHourDto;
use App\Application\Service\Auth\AuthActorService;
use App\Domains\ExceptionHour\Entity\ExceptionHour;
use App\Domains\ExceptionHour\Factory\ExceptionHourFactory;
use App\Domains\ExceptionHour\Repositories\ExceptionHourRepositoryInterface;
use App\Exceptions\DomainException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncOwnExceptionHoursByDateService
{
    public function __construct(
        private AuthActorService $authActorService,
        private ExceptionHourRepositoryInterface $exceptionHourRepository,
        private ExceptionHourFactory $exceptionHourFactory,
    ) {
    }

    public function execute(ExceptionHourDto $exceptionHourDto)
    {
        $hospitalId = $this->authActorService->getHospitalId();
        // トランザクション範囲を最初限にするため、トランザクションの外で実行する
        $entities = $this->exceptionHourFactory->dtoToEntities(dto: $exceptionHourDto, hospitalId: $hospitalId);

        $this->validateIsClose($entities);
        $this->validateOverlaps($entities);

        $insertRows = $this->exceptionHourFactory->entitesToInsertRows($entities);

        try {
            DB::transaction(function () use ($hospitalId, $exceptionHourDto, $insertRows) {
                $this->exceptionHourRepository->deleteByDateInHospital(
                    hospitalId: $hospitalId,
                    date: $exceptionHourDto->getDate(),
                );

                $this->exceptionHourRepository->createMany($insertRows);
            });

            return true;
        } catch (Throwable $e) {
            Log::error('Exception hour sync failed', ['error' => $e]);
            return false;
        }
    }

    /**
     * 休診データのチェックをする
     * @param ExceptionHour[] $entities
     * @return void
     * @throws DomainException
     */
    private function validateIsClose(array $entities): void
    {
        $closed = array_filter($entities, fn ($entity) => $entity->getIsClosed()->getValue());

        if (count($closed) > 1) {
            throw new DomainException('休診データは1件にしてください。');
        }

        if (!empty($closed) && count($entities) > 1) {
            throw new DomainException('休診データがある場合は他のデータを含められません。');
        }
    }

    /**
     * 時間が重複してないことをチェックする
     * @param ExceptionHour[] $entities
     * @return void
     * @throws DomainException
     */
    private function validateOverlaps(array $entities): void
    {
        if (count($entities) <= 1) {
            // 一つだと重複しない
            return;
        }

        // start_timeとend_timeの相関チェックはStoreBusinessHourRequestで実施済み
        $periods = array_map(
            fn ($entity) => [
                // fixme stringを渡すように修正する
                'start_time' => $entity->getStartTime(),
                'end_time'   => $entity->getEndTime(),
            ],
            $entities,
        );

        // 重複チェックをするために開始時間で昇順にする
        usort($periods, fn ($a, $b) => strcmp($a['start_time'], $b['start_time']));

        $conflicts = [];
        for ($i = 0; $i < count($periods) - 1; $i++) {
            $current = $periods[$i];
            $next    = $periods[$i + 1];

            // 現在の end_time が次の start_time より大きい場合は重複している
            if ($current['end_time'] > $next['start_time']) {
                $conflicts[] = sprintf(
                    '%s-%s と %s-%s',
                    $current['start_time'],
                    $current['end_time'],
                    $next['start_time'],
                    $next['end_time'],
                );
            }
        }

        if (! empty($conflicts)) {
            throw new DomainException(
                '営業時間が重複しています: ' . implode(', ', $conflicts),
            );
        }
    }

}
