<?php

namespace App\Data\Shared;

use Spatie\LaravelData\Data;

/**
 * Class GenericData
 *
 * @package App\Data\Shared
 *
 * Represents a generic data container that includes metadata and user data.
 *
 * @property MetaData|null $metaData Metadata associated with the generic data.
 * @property UserData|null $userData User data associated with the generic data.
 */
class GenericData extends Data
{
    /**
     * GenericData constructor.
     *
     * @param MetaData|null $metaData Metadata associated with the generic data.
     * @param UserData|null $userData User data associated with the generic data.
     */
    public function __construct(
        public ?MetaData $metaData = null,
        public ?UserData $userData = null,
    ) {
        $this->metaData = $metaData ?? new MetaData();
        $this->userData = $userData ?? new UserData();
    }
}
