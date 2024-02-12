<?php

namespace App\Constants;

use ReflectionClass;

/**
 * Class BaseConstant
 *
 * Base class for constants with common functionality.
 *
 * @package App\Constants
 */
class BaseConstant
{
    /**
     * Get an array of all constants defined in the child class.
     *
     * @return array
     */
    public static function getAllConstants(): array
    {
        $reflectionClass = new ReflectionClass(static::class);
        return $reflectionClass->getConstants();
    }

    /**
     * Get an array of all constant values defined in the child class.
     *
     * @return array
     */
    public static function getAllConstantValues(): array
    {
        $reflectionClass = new ReflectionClass(static::class);
        return array_values($reflectionClass->getConstants());
    }
}
