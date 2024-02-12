<?php

namespace App\Constants;

/**
 * Class AppConstant
 *
 * Constants representing various application settings and values.
 *
 * @package App\Constants
 */
class AppConstant
{
    /**
     * Default page number for pagination.
     */
    const DEFAULT_PAGE = 1;

    /**
     * Default limit for items per page in pagination.
     */
    const DEFAULT_PAGE_LIMIT = 10;

    /**
     * Maximum limit for items per page in pagination.
     */
    const MAX_PAGE_LIMIT = 100;

    /**
     * Default precision for decimal numbers.
     */
    const DECIMAL_PRECISION = 2;

    /**
     * Alphanumeric characters for generating random strings.
     */
    const CHARACTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

    /**
     * Regular expression pattern for matching numeric values.
     */
    const NUMERIC = '[0-9]+';

    /**
     * Regular expression pattern for matching lowercase letters.
     */
    const LETTERS = '[a-z]+';

    /**
     * Regular expression pattern for matching uuid values.
     */
    const UUID_PATTERN = '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[1-5][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}';
}
