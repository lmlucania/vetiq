<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    public function render($request)
    {
        $message = $this->getMessage() ?: 'The requested resource was not found.';
        return response()->json([
            'error'   => 'Not Found',
            'message' => $message,
        ], 404);
    }
}
