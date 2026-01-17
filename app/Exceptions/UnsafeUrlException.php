<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class UnsafeUrlException extends BaseException
{
    protected $statusCode = Response::HTTP_FORBIDDEN;
    protected $message = "The provided URL is flagged as unsafe.";
}
