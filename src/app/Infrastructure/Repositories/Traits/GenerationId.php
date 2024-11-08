<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\Traits;

use Illuminate\Support\Facades\DB;

trait GenerationId
{
    public function generateId(string $modelClass): int
    {
        $modelInstance = new $modelClass();
        $table         = $modelInstance->getTable();
        $statement     = DB::select("SHOW TABLE STATUS LIKE '{$table}'");
        return $statement[0]->Auto_increment;
    }
}
