<?php

namespace App\Constants;

use App\Constants\BaseConstant;

/**
 * Class ModuleConstant
 *
 * Constants representing various modules.
 *
 * @package App\Constants
 */
class ModuleConstant extends BaseConstant
{
    /**
     * Valid modules.
     */
    const SHARED = 'Shared';
    const EXAMPLE = 'Example';

    /**
     * Get an associative array of module names and their corresponding route values.
     *
     * @return array
     */
    public static function getModuleRoutes(): array
    {
        return [
            self::SHARED => 'shared',
            self::EXAMPLE => 'example',
            // Add more module routes as needed
        ];
    }
}
