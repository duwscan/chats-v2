<?php

use App\Exceptions\CustomException;
use App\Http\Controllers\ApiResponseTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $responder = new class {
            use ApiResponseTrait;
        };

        $exceptions->render(function (CustomException $exception, Request $request) use ($responder) {
            if (! $request->expectsJson()) {
                return null;
            }

            return $responder->responseFail($exception->getMessage(), $exception->data(), $exception->status());
        });

        $exceptions->render(function (ModelNotFoundException $exception, Request $request) use ($responder) {
            if (! $request->expectsJson()) {
                return null;
            }

            return $responder->responseFail($exception);
        });

        $exceptions->render(function (ValidationException $exception, Request $request) use ($responder) {
            if (! $request->expectsJson()) {
                return null;
            }

            return $responder->responseFail(
                'Validation failed.',
                ['errors' => $exception->errors()],
                ResponseAlias::HTTP_UNPROCESSABLE_ENTITY,
            );
        });

        $exceptions->render(function (AuthenticationException $exception, Request $request) use ($responder) {
            if (! $request->expectsJson()) {
                return null;
            }

            return $responder->responseFail(
                $exception->getMessage() ?: 'Unauthenticated.',
                [],
                ResponseAlias::HTTP_UNAUTHORIZED,
            );
        });

        $exceptions->render(function (AuthorizationException $exception, Request $request) use ($responder) {
            if (! $request->expectsJson()) {
                return null;
            }

            return $responder->responseFail(
                $exception->getMessage() ?: 'Forbidden.',
                [],
                ResponseAlias::HTTP_FORBIDDEN,
            );
        });

        $exceptions->render(function (HttpExceptionInterface $exception, Request $request) use ($responder) {
            if (! $request->expectsJson()) {
                return null;
            }

            return $responder->responseFail(
                $exception->getMessage() ?: ResponseAlias::statusText($exception->getStatusCode()),
                [],
                $exception->getStatusCode(),
            );
        });

        $exceptions->render(function (Throwable $exception, Request $request) use ($responder) {
            if (! $request->expectsJson()) {
                return null;
            }

            return $responder->responseFail(
                $exception,
                null,
                ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
            );
        });
    })->create();
