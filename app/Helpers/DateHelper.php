<?php

namespace App\Helpers;

use Carbon\Exceptions\InvalidFormatException;
use DateTimeZone;
use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class DateHelper
{

    /**
     * Convert date string to app timezone carbon.
     * This will use the application's default timezone specified in config/env.
     *
     * @param string|null $dateString
     * E.g. "2022-03-02T00:00:00.000000Z" or "2022-03-02 19:40:29"
     *
     * @return Carbon|null
     */
    public static function toCarbon(?string $dateString): ?Carbon
    {
        if (empty($dateString)) {
            return null;
        }

        try {
            $parsedCarbon = Carbon::parse($dateString);

            return self::toAppTimezone($parsedCarbon);
        } catch (InvalidFormatException $exception) {
            Log::error("DateStringToAppCarbonException: {$exception->getMessage()}");
        }

        return null;
    }

    /**
     * Convert carbon datetime to app timezone carbon.
     * This will use the application's default timezone specified in config/env.
     *
     * @param Carbon|null $dateTime
     *
     * @return Carbon|null
     */
    public static function toAppTimezone(?Carbon $dateTime): ?Carbon
    {
        if (empty($dateTime) || !$dateTime->isValid()) {
            return null;
        }

        $timezone = new DateTimeZone(config('app.timezone'));
        $dateTime->setTimezone($timezone);

        return $dateTime;
    }

    /**
     * Convert date string to UTC timezone carbon.
     *
     * @param string|null $dateString
     * E.g. "2022-03-02T00:00:00.000000Z" or "2022-03-02 19:40:29"
     *
     * @return Carbon|null
     */
    public static function toUTCCarbon(?string $dateString): ?Carbon
    {
        if (empty($dateString)) {
            return null;
        }

        try {
            $parsedCarbon = Carbon::parse($dateString);

            return self::toUTCTimezone($parsedCarbon);
        } catch (InvalidFormatException $exception) {
            Log::error("DateStringToUTCCarbonException: {$exception->getMessage()}");
        }

        return null;
    }

    /**
     * Convert carbon datetime to UTC timezone carbon.
     *
     * @param Carbon|null $dateTime
     *
     * @return Carbon|null
     */
    public static function toUTCTimezone(?Carbon $dateTime): ?Carbon
    {
        if (empty($dateTime) || !$dateTime->isValid()) {
            return null;
        }

        $timezone = new DateTimeZone('UTC');
        $dateTime->setTimezone($timezone);

        return $dateTime;
    }

    /**
     * Convert ShipStation API date string to app timezone carbon.
     *
     * @param string|null $dateString
     * E.g. "2022-03-02T00:00:00.0000000" or "2022-03-02 19:40:29"
     *
     * @return Carbon|null
     */
    public static function fromShipStationAPIDateToAppTimezone(?string $dateString): ?Carbon
    {
        if (empty($dateString)) {
            return null;
        }

        try {
            $parsedCarbon = Carbon::parse($dateString, config('custom.shipstation_api_timezone'));
            $parsedCarbon->setTimezone(config('app.timezone'));

            return $parsedCarbon;
        } catch (InvalidFormatException $exception) {
            Log::error("FromShipStationAPIDateToAppTimezoneException: {$exception->getMessage()}");
        }

        return null;
    }

    /**
     * Convert date string to ShipStation API timezone carbon.
     *
     * @param string|null $dateString
     * E.g. "2022-03-02T00:00:00.000000Z" or "2022-03-02 19:40:29"
     *
     * @return Carbon|null
     */
    public static function toShipStationAPICarbon(?string $dateString): ?Carbon
    {
        if (empty($dateString)) {
            return null;
        }

        try {
            $parsedCarbon = Carbon::parse($dateString);

            return self::toShipStationAPITimezone($parsedCarbon);
        } catch (InvalidFormatException $exception) {
            Log::error("ToShipStationAPICarbonException: {$exception->getMessage()}");
        }

        return null;
    }

    /**
     * Convert carbon datetime to ShipStation API timezone carbon.
     *
     * @param Carbon|null $dateTime
     *
     * @return Carbon|null
     */
    public static function toShipStationAPITimezone(?Carbon $dateTime): ?Carbon
    {
        if (empty($dateTime) || !$dateTime->isValid()) {
            return null;
        }

        try {
            $timezone = new DateTimeZone(config('custom.shipstation_api_timezone'));
            $dateTime->setTimezone($timezone);

            return $dateTime;
        } catch (Exception $exception) {
            Log::error("ToShipStationAPITimezoneException: {$exception->getMessage()}");
        }

        return null;
    }

    /**
     * Generate dates for date range.
     *
     * @param Carbon $startDate
     * @param Carbon $endDate
     *
     * @return array
     */
    public static function generateDateRange(Carbon $startDate, Carbon $endDate): array
    {
        $dates = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dates[] = $date->format('Y-m-d');
        }

        return $dates;
    }

    /**
     * Convert carbon datetime to unix timestamp (seconds).
     *
     * @param Carbon|null $dateTime
     *
     * @return int
     */
    public static function toTimestamp(?Carbon $dateTime): int
    {
        $timestamp = 0;

        if (!empty($dateTime) && $dateTime->isValid()) {
            $timestamp = $dateTime->getTimestamp();
        }

        return $timestamp;
    }

    /**
     * Convert date string to ISO string (UTC).
     *
     * @param $dateString
     *
     * @return string
     */
    public static function toISOString(?string $dateString): ?string
    {
        $utcCarbon = self::toCarbon($dateString);

        if (empty($utcCarbon)) {
            return null;
        }

        return $utcCarbon->toISOString();
    }

    /**
     * Convert UTC date string to Carbon ISO string.
     *
     * @param $dateString
     *
     * @return string
     */
    public static function parseUTCtoISOString(?string $dateString): ?string
    {
        $utcCarbon = self::toUTCCarbon($dateString);

        if (empty($utcCarbon)) {
            return null;
        }

        return $utcCarbon->toISOString();
    }

    /**
     * Parse datetime string to UTC Carbon.
     *
     * @param string|null $dateString
     *
     * @return Carbon|null
     */
    public static function parseDatetimeStringToUTCCarbon(?string $dateString): ?Carbon
    {
        if (empty($dateString)) {
            return null;
        }

        try {
            return Carbon::parse($dateString, 'UTC');
        } catch (InvalidFormatException $exception) {
            Log::error("ParseDatetimeStringToUTCCarbon: {$exception->getMessage()}");
        }

        return null;
    }

    /**
     * @param Carbon $datetime
     *
     * @return Carbon
     */
    public static function convertToShipStationDateTime(Carbon $datetime): Carbon
    {
        $timezone = new DateTimeZone(env('SHIPSTATION_API_TIMEZONE', 'America/Los_Angeles'));
        $datetime = $datetime->setTimezone($timezone);

        return $datetime;
    }

    /**
     * Set date time to current time.
     *
     * @param Carbon $datetime
     *
     * @return Carbon
     */
    public static function setToCurrentTime(Carbon $datetime): Carbon
    {
        $currentDate = Carbon::now();
        $datetime = $datetime->setTime(
            $currentDate->hour,
            $currentDate->minute,
            $currentDate->second
        );

        return $datetime;
    }
}