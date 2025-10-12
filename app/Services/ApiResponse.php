<?php

namespace App\Services;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Validation\ValidationException;
use Throwable;

class ApiResponse
{
    /**
     * Send a generic JSON response.
     */
    public function send(
        bool $success,
        string $message,
        string $code = 'success',
        mixed $result = null,
        int $statusCode = 200,
        array $headers = [],
        array $extra = []
    ): JsonResponse {
        $response = array_merge([
            'success' => $success,
            'code'    => $code,
            'message' => $message,
            'result'  => $result,
        ], $extra);

        return response()->json($response, $statusCode, $headers);
    }

    /**
     * Success response (generic).
     */
    public function success(
        mixed $data = null,
        string $message = 'Success',
        string $code = 'success',
        int $statusCode = 200,
        array $headers = [],
        array $extra = []
    ): JsonResponse {
        return $this->send(true, $message, $code, $data, $statusCode, $headers, $extra);
    }

    /**
     * Return a single resource.
     */
    public function resource(
        JsonResource $resource,
        string $message = 'Success',
        string $code = 'success',
        int $statusCode = 200,
        array $headers = []
    ): JsonResponse {
        return $this->success($resource, $message, $code, $statusCode, $headers);
    }

    /**
     * Return a resource collection.
     */
    public function collection(
        ResourceCollection $collection,
        string $message = 'Success',
        string $code = 'success',
        int $statusCode = 200,
        array $headers = [],
        array $additional = []
    ): JsonResponse {
        $data = array_merge($collection->response()->getData(true), $additional ?? []);
        return $this->success($data, $message, $code, $statusCode, $headers);
    }

    /**
     * Validation error response.
     */
    public function validationError(ValidationException $e): JsonResponse
    {
        return $this->error(
            $e->getMessage(),
            'validation_error',
            422,
            $e,
            ['errors' => $e->errors()]
        );
    }

    /**
     * General error handler.
     */
    public function error(
        string $message,
        string $code = 'error',
        int $statusCode = 400,
        ?Throwable $exception = null,
        array $extra = []
    ): JsonResponse {
        $payload = [
            'success' => false,
            'code'    => $code,
            'message' => $message,
        ];

        if ($exception && config('app.env') !== 'production') {
            $payload['exception'] = [
                'message' => $exception->getMessage(),
                'file'    => $exception->getFile(),
                'line'    => $exception->getLine(),
                'code'    => $exception->getCode(),
                'trace'   => collect($exception->getTrace())->take(5), // limit trace
            ];
        }

        return response()->json(array_merge($payload, $extra), $statusCode);
    }

    /**
     * Common error shortcuts.
     */
    public function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->error($message, 'unauthorized', 401);
    }

    public function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return $this->error($message, 'forbidden', 403);
    }

    public function notFound(string $message = 'Not Found'): JsonResponse
    {
        return $this->error($message, 'not_found', 404);
    }

    public function serverError(string $message = 'Internal Server Error', ?Throwable $e = null): JsonResponse
    {
        return $this->error($message, 'server_error', 500, $e);
    }
}
