<?php

namespace App\Data\Shared;

use Spatie\LaravelData\Data;

/**
 * Class UserData
 *
 * @package App\Data\Shared
 *
 * @property string|null $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $emailVerifiedAt
 * @property string|null $createdAt
 * @property string|null $updatedAt
 */
class UserData extends Data
{
    /**
     * UserData constructor.
     *
     * @param string|null $id
     * @param string|null $name
     * @param string|null $email
     * @param string|null $emailVerifiedAt
     * @param string|null $createdAt
     * @param string|null $updatedAt
     */
    public function __construct(
        public ?string $id = null,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $emailVerifiedAt = null,
        public ?string $createdAt = null,
        public ?string $updatedAt = null,
    ) {
    }
}
