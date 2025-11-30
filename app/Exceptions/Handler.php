<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
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

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $e)
    {
        // Handle CSRF token mismatch (419 error)
        if ($e instanceof \Illuminate\Session\TokenMismatchException) {
            return redirect()->back()
                ->withInput()
                ->with('status', 'Your session has expired. Please try again.')
                ->with('toast_type', 'error');
        }

        return parent::render($request, $e);
    }
}
