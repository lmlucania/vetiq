<?php

declare(strict_types=1);

namespace App\Application\QueryService;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TagQueryService
{
    public function getTagsWithSelectionByHospitalId(int $hospitalId): Collection
    {
        return DB::table('tag_categories')
            ->join('tags', 'tag_categories.id', '=', 'tags.tag_category_id')
            ->leftJoin('hospital_tag', function ($subQuery) use ($hospitalId) {
                $subQuery->on('hospital_tag.tag_id', '=', 'tags.id')
                    ->where('hospital_tag.hospital_id', '=', $hospitalId);
            })
            ->select(
                'tag_categories.id as category_id',
                'tag_categories.name as category_name',
                'tag_categories.display_order as category_display_order',
                'tags.id as tag_id',
                'tags.name as tag_name',
                'tags.display_order as tag_display_order',
                DB::raw('CASE WHEN hospital_tag.id IS NULL THEN false ELSE true END AS is_selected'),
            )
            ->orderBy('category_display_order')
            ->orderBy('tag_display_order')
            ->get()
            ->groupBy('category_id');
    }
}
