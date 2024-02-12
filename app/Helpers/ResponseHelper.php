<?php

namespace App\Helpers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ResponseHelper
{
    /**
     * Return JSON response with the given data and status code.
     *
     * @param mixed $data
     * @param int $status
     * @return JsonResponse
     */
    public static function json($data, int $status = SymfonyResponse::HTTP_OK): JsonResponse
    {
        return response()->json($data, $status);
    }

    /**
     * Return an empty JSON response with a 200 (OK) status code.
     *
     * @return JsonResponse
     */
    public static function ok(): JsonResponse
    {
        return self::json([], SymfonyResponse::HTTP_OK);
    }

    /**
     * Return an empty JSON response with a 200 (OK) status code.
     *
     * @return JsonResponse
     */
    public static function empty(): JsonResponse
    {
        return self::json(null, SymfonyResponse::HTTP_OK);
    }

    /**
     * Return a JSON response for a resource or collection.
     *
     * @param string $resource
     * @param mixed $data
     * @return JsonResponse|JsonResource
     */
    public static function resource(string $resource, $data)
    {
        $response = self::empty();

        if (
            $data instanceof Model
            && method_exists($data, 'isPresent')
            && $data->isPresent()
            || (
                $data instanceof Model && !empty($data->id)
            )
        ) {
            $response = new $resource($data);
        } elseif (is_array($data) && !empty($data)) {
            $response = new $resource((object) $data);
        } elseif (
            $data instanceof LengthAwarePaginator
            || $data instanceof Collection
        ) {
            $response = $resource::collection($data);
        }

        return $response;
    }

    /**
     * Return a success message with a 200 (OK) status code.
     *
     * @param string $message
     * @param string $key
     * @return JsonResponse
     */
    public static function success(string $message, string $key = 'success'): JsonResponse
    {
        return self::json([$key => $message], SymfonyResponse::HTTP_OK);
    }

    /**
     * Return an error message with a specified status code.
     *
     * @param string $message
     * @param int $errorCode
     * @param string $key
     * @return JsonResponse
     */
    public static function error(string $message, int $errorCode = SymfonyResponse::HTTP_BAD_REQUEST, string $key = 'error'): JsonResponse
    {
        return self::json([$key => $message], $errorCode);
    }

    /**
     * Return an unauthorized error message with a 401 (Unauthorized) status code.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function unauthorized(string $message = 'Unauthorized.'): JsonResponse
    {
        return self::json(['error' => $message], SymfonyResponse::HTTP_UNAUTHORIZED);
    }

    /**
     * Return a not found error message with a 404 (Not Found) status code.
     *
     * @param string $message
     * @return JsonResponse
     */
    public static function notFound(string $message = 'The requested resource could not be found.'): JsonResponse
    {
        return self::json(['error' => $message], SymfonyResponse::HTTP_NOT_FOUND);
    }
}