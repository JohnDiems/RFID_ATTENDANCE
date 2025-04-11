<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, \Throwable $exception)
    {
        if ($exception instanceof HttpException && $exception->getStatusCode() == 419) {
            // CSRF token mismatch error
            if ($request->expectsJson()) {
                return response()->json(['error' => 'CSRF token mismatch'], 419);
            } else {
                // You can customize this part to fit your needs
                // For example, redirect back with a notification
                return redirect()->back()->with('notification', 'CSRF token mismatch');
            }
        }

        return parent::render($request, $exception);
    }
}
