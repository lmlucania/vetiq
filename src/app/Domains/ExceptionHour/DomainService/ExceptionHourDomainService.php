<?php

namespace App\Domains\ExceptionHour\DomainService;

use App\Domains\ExceptionHour\Entity\ExceptionHour;
use App\Exceptions\DomainException;

class ExceptionHourDomainService
{
    /**
     * 登録時のバリデーションをする
     * @param ExceptionHour[] $entities
     * @return void
     * @throws DomainException
     */
    public function validateBeforeCreate(array $entities): void
    {
        $this->ensureClosedHoursAreValid($entities);
        $this->ensureNoBusinessHourOverlap($entities);
    }

    /**
     * 休診データのチェックをする
     * @param ExceptionHour[] $entities
     * @return void
     * @throws DomainException
     */
    private function ensureClosedHoursAreValid(array $entities): void
    {
        $totalCount = count($entities);
        $closedCount = count(array_filter($entities, fn ($entity) => $entity->getIsClosed()->getValue()));

        if ($totalCount <= 1 || $closedCount === 0) {
            return;
        }

        if ($totalCount === $closedCount) {
            throw new DomainException('1日につき休診日は1件のみ登録可能です。');
        } else {
            throw new DomainException('休診日と営業時間は同じ日に併用できません。');
        }
    }

    /**
     * 時間が重複してないことをチェックする
     * @param ExceptionHour[] $entities
     * @return void
     * @throws DomainException
     */
    private function ensureNoBusinessHourOverlap(array $entities): void
    {
        if (count($entities) <= 1) {
            // データが一つの場合は重複しない
            return;
        }

        // start_timeとend_timeの相関チェックはStoreBusinessHourRequestで実施済み
        $periods = [];
        foreach ($entities as $entity) {
            if ($entity->hasBusinessHours()) {
                $periods[] = [
                    'start_time' => $entity->getStartTime()?->toString(),
                    'end_time'   => $entity->getEndTime()?->toString(),
                ];
            }
        }

        if (count($periods) <= 1) {
            // データが一つの場合は重複しない
            return;
        }

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
