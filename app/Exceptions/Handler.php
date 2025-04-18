<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        ActionCouldNotBeProcessed::class,
        TestException::class,
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, Request $request) {
            // cater for unhandled exceptions on inertia form submissions
            if ($request->header('X-Inertia') && $request->method() === 'POST' && ! $request->expectsJson()) {
                if ($e instanceof ValidationException) {
                    return; // allow laravel take care of validation exceptions and send the response in its normal form
                }

                $exceptionMessage = 'Server Error';

                if ($e instanceof HttpException || config('app.debug')) {
                    $exceptionMessage = $e->getMessage();
                }

                return back()->with('error', $exceptionMessage);
            }

            if ($e instanceof AccessDeniedHttpException) {
                $message = $e->getMessage();

                return $request->expectsJson()
                    ? response()->json(['message' => $message], 404)
                    : back()->with('error', $message);
            }

            if ($e instanceof NotFoundHttpException) {
                $message = with($e->getMessage(), function ($message) {
                    return blank($message) || Str::contains($message, 'No query results for model')
                        ? 'Resource not found.'
                        : $message;
                });

                return $request->expectsJson()
                    ? response()->json(['message' => $message], 404)
                    : back()->with('error', $message);
            }
        });
    }

    public function render($request, Throwable $e)
    {
        if ($e instanceof NotFoundHttpException && $request->segment(1) === 'checkout') {
            return Inertia::render('Error', [
                'status' => 404,
                'message' => 'Sorry, this link has expired or is invalid',
            ])->toResponse($request)->setStatusCode(404);
        }

        return parent::render($request, $e);
    }
}
