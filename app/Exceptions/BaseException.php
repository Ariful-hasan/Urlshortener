<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class BaseException extends Exception
{
    // Default status code
    protected $statusCode = Response::HTTP_BAD_REQUEST;

    public function render($request): JsonResponse
    {
        return response()->json([
            config('constants.SUCCESS') => false,
            config('constants.MESSAGE') => $this->getMessage(),
        ], $this->statusCode);
    }
}
