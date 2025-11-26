<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\CursorPaginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Throwable;

trait ApiResponseTrait
{
    protected static array $extraResponse = [
        '_block' => null,
    ];

    public static function addBlockResponseMessage(string $message, bool $fresh = false): void
    {
        if ($fresh || self::$extraResponse['_block'] === null) {
            self::$extraResponse['_block'] = [];
        }

        self::$extraResponse['_block'][] = $message;
    }

    public static function addBlockResponse(string $key, mixed $value, bool $fresh = false): void
    {
        if ($fresh || empty(self::$extraResponse[$key])) {
            self::$extraResponse[$key] = [];
        }

        self::$extraResponse[$key] = $value;
    }

    protected function response(bool $failed, mixed $data = null, string $message = '', ?int $customStatus = null): JsonResponse
    {
        $status = $customStatus ?? ($failed ? ResponseAlias::HTTP_UNPROCESSABLE_ENTITY : ResponseAlias::HTTP_OK);

        return response()->json([
            '_status' => $status,
            '_success' => ! $failed,
            '_messages' => $message === '' ? null : (array) $message,
            '_data' => $data,
            '_extra' => self::$extraResponse,
        ], $status);
    }

    protected function responseSuccess(mixed $data = null, string $message = '', ?int $customStatus = null): JsonResponse
    {
        $this->addPaginationBlock($data);
        $this->addCursorBlock($data);

        return $this->response(false, $data, $message, $customStatus);
    }

    public function addPaginationBlock(mixed &$data): void
    {
        if ($data instanceof ResourceCollection && $data->resource instanceof LengthAwarePaginator) {
            $data = $data->resource;
        } elseif (! $data instanceof LengthAwarePaginator) {
            return;
        }

        $this->addBlockResponse('pagination', [
            'total' => $data->total(),
            'per_page' => $data->perPage(),
            'current_page' => $data->currentPage(),
            'last_page' => $data->lastPage(),
            'from' => $data->firstItem(),
            'to' => $data->lastItem(),
        ]);

        $data = $data->items();
    }

    public function addCursorBlock(mixed &$data): void
    {
        if ($data instanceof ResourceCollection && $data->resource instanceof CursorPaginator) {
            $data = $data->resource;
        } elseif (! $data instanceof CursorPaginator) {
            return;
        }

        $this->addBlockResponse('cursor_pagination', [
            'per_page' => $data->perPage(),
            'next_cursor' => $data->nextCursor()?->encode(),
            'prev_cursor' => $data->previousCursor()?->encode(),
            'has_more_pages' => $data->hasMorePages(),
        ]);

        $data = $data->items();
    }

    protected function responseFail(mixed $message = '', mixed $data = null, ?int $customStatus = null): JsonResponse
    {
        if ($message instanceof ModelNotFoundException) {
            return $this->response(true, null, '404 - '.trans('error_http.404'), ResponseAlias::HTTP_NOT_FOUND);
        }

        if ($message instanceof Throwable) {
            $exception = $message;
            $message = $exception->getMessage();
            $data = array_merge((array) $data, [
                'exception' => [
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ],
            ]);

            if (config('app.env') === 'production' && config('app.debug') === false) {
                return $this->response(true, [], __('error.level_3_failed'), $customStatus);
            }

            return $this->response(true, $data, $message, $customStatus);
        }

        return $this->response(true, $data, $message, $customStatus);
    }
}
