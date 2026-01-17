<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // 1. LOGGING: Keep this to catch all errors in logs
        $this->reportable(function (Throwable $e) {
            Log::error('App Error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
        });

        // 2. Catch ModelNotFound (firstOrFail)
        $this->renderable(function (ModelNotFoundException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    config('constants.SUCCESS') => false,
                    config('constants.MESSAGE') => "The requested entity was not found.",
                ], Response::HTTP_NOT_FOUND);
            }
        });

        // 3. GLOBAL FALLBACK: For "Internal Server Errors" (500)
        $this->renderable(function (Throwable $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    config('constants.SUCCESS') => false,
                    config('constants.MESSAGE') => "Something went wrong! ",
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        });
    }
}
