<?php

namespace App\Data\Shared;

use Spatie\LaravelData\Data;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ResponseData
 *
 * @package App\Data\Shared
 *
 * Represents a standardized response format with success/error status, HTTP code, status message, data, and headers.
 *
 * @property bool $success Whether the response is successful.
 * @property string $message Message associated with the response.
 * @property string $status Status message of the response.
 * @property int $code HTTP status code of the response.
 * @property mixed $data Raw data associated with the response.
 * @property array $headers Headers associated with the response.
 */
class ResponseData extends Data
{
    /**
     * ResponseData constructor.
     *
     * @param bool $success Whether the response is successful.
     * @param string $message Message associated with the response.
     * @param string $status Status message of the response.
     * @param int $code HTTP status code of the response.
     * @param mixed $data Raw data associated with the response.
     * @param array $headers Headers associated with the response.
     */
    public function __construct(
        private bool $success = false,
        private string $message = '',
        private string $status = '',
        private int $code = 0,
        private mixed $data = null,
        private array $headers = [],
    ) {
    }

    /**
     * Check if the response is a success.
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->success;
    }

    /**
     * Set the success status of the response.
     *
     * @param bool $success
     */
    public function setSuccess(bool $success): void
    {
        $this->success = $success;
    }

    /**
     * Check if the response is an error.
     *
     * @return bool
     */
    public function isError(): bool
    {
        return !$this->success;
    }

    /**
     * Set the success status of the response to false.
     *
     * @param bool $success
     */
    public function setError(): void
    {
        $this->success = false;
    }

    /**
     * Get the HTTP status code of the response.
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * Set the HTTP status code of the response.
     *
     * @param int $code
     */
    public function setCode(int $code): void
    {
        $this->code = $code;
    }

    /**
     * Get the status message of the response.
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Set the status message of the response.
     *
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * Get the message associated with the response.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Set the message associated with the response.
     *
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * Get the raw data associated with the response.
     *
     * @return mixed
     */
    public function getData(): mixed
    {
        return $this->data;
    }

    /**
     * Set the raw data associated with the response.
     *
     * @param mixed $data
     */
    public function setData(mixed $data): void
    {
        $this->data = $data;
    }

    /**
     * Get the headers associated with the response.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * Set the headers associated with the response.
     *
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * Create a success response.
     *
     * @param string $message
     * @param mixed|null $data
     * @param int $code
     * @param array $headers
     *
     * @return self
     */
    public static function success(
        string $message,
        mixed $data = null,
        int $code = Response::HTTP_OK,
        array $headers = []
    ): self {
        $response = new self();
        $response->setSuccess(true);
        $response->setMessage($message);
        $response->setData($data);
        $response->setCode($code);
        $response->setHeaders($headers);

        return $response;
    }

    /**
     * Create an error response.
     *
     * @param string $message
     * @param mixed|null $data
     * @param int $code
     * @param array $headers
     *
     * @return self
     */
    public static function error(
        string $message,
        mixed $data = null,
        int $code = Response::HTTP_BAD_REQUEST,
        array $headers = []
    ): self {
        $response = new self();
        $response->setSuccess(false);
        $response->setMessage($message);
        $response->setData($data);
        $response->setCode($code);
        $response->setHeaders($headers);

        return $response;
    }

    /**
     * Create a mapped response.
     *
     * @param mixed|null $data
     * @param int $code
     * @param array $headers
     *
     * @return self
     */
    public static function map(
        mixed $data = null,
        int $code = Response::HTTP_OK,
        array $headers = []
    ): self {
        $response = new self();
        $response->setSuccess(true);
        $response->setMessage('Success');
        $response->setData($data);
        $response->setCode($code);
        $response->setHeaders($headers);

        return $response;
    }
}
