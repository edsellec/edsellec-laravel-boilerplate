<?php

namespace App\Constants;

use App\Constants\BaseConstant;

/**
 * Class StatusConstant
 *
 * Constants representing different statuses for entities.
 *
 * @package App\Constants
 */
class StatusConstant extends BaseConstant
{
    /**
     * Entity is in a draft or incomplete state.
     */
    const DRAFT = 'DRAFT';

    /**
     * Entity is live or publicly accessible.
     */
    const PUBLISHED = 'PUBLISHED';

    /**
     * Entity is no longer in active use but is retained for historical purposes.
     */
    const ARCHIVED = 'ARCHIVED';

    /**
     * Get a human-readable label for a status constant.
     *
     * @param string $status
     * @return string|null
     */
    public static function getConstantLabel(string $status): ?string
    {
        switch ($status) {
            case self::DRAFT:
                return 'Draft';
            case self::PUBLISHED:
                return 'Published';
            case self::ARCHIVED:
                return 'Archived';
            default:
                return null;
        }
    }
}
