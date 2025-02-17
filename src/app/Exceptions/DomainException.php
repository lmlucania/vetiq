<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class DomainException extends Exception
{
    public function render($request)
    {
        $message = $this->getMessage() ?: 'The request could not be processed due to a business rule violation.';
        return response()->json([
            'error'   => 'Unprocessable Entity',
            'message' => $message,
        ], 422);
    }
}
