<?php

declare(strict_types=1);

namespace App\Application\QueryService\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait SortableQuery
{
    /**
     * クエリにソートを適用する
     * @param Builder $query Eloquentクエリビルダーインスタンス
     * @param array $sortable ソート可能なカラムのリスト
     * @param array $sortParams ソート条件の配列。例: ['id', '-name']
     * @return Builder ソートが適用されたEloquentクエリビルダーインスタンス
     */
    public function querySort(Builder $query, array $sortable, array $sortParams): Builder
    {
        $sortCriteria = array_map(fn (string $param) => [
            'param'     => ltrim($param, '-'),
            'direction' => Str::startsWith($param, '-') ? 'desc' : 'asc',
        ], array_filter($sortParams));

        foreach ($sortCriteria as $criteria) {
            if (in_array($criteria['param'], $sortable)) {
                $query->orderBy($criteria['param'], $criteria['direction']);
            }
        }

        return $query;
    }
}
