<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class InvalidArgumentException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'error'   => 'Invalid argument',
            'message' => $this->getMessage(),
        ], 400);
    }
}
