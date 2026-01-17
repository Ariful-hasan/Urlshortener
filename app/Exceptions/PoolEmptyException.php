<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class PoolEmptyException extends BaseException
{
    protected $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;

    protected $message = "No Short Code available.";
}
