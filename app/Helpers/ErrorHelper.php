<?php

namespace App\Helpers;

use App\Data\Shared\ResponseData;
use Illuminate\Support\Facades\Log;
use Throwable;

class ErrorHelper
{
    /**
     * Generate error response.
     *
     * @param string $class
     * @param string $action
     * @param Throwable $e
     * 
     * @return ResponseData
     */
    public static function generateErrorResponse(string $class, string $action, Throwable $e): ResponseData
    {
        $logMessage = self::createLogMessage($class, $action, $e);
        Log::error($logMessage);

        return ResponseData::error($logMessage);
    }

    /**
     * Create log message.
     *
     * @param string $class
     * @param string $action
     * @param Throwable $e
     * 
     * @return string
     */
    public static function createLogMessage(string $class, string $action, Throwable $e): string
    {
        return "Error in {$class} - Action: {$action}, Message: {$e->getMessage()}, Line: {$e->getLine()}";
    }
}
