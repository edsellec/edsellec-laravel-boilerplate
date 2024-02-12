<?php

namespace App\Data\Auth;

use Spatie\LaravelData\Data;

/**
 * Class AuthData
 *
 * @package App\Data\Auth
 *
 * @property int|null $id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $password
 * @property string|null $accessToken
 */
class AuthData extends Data
{
    /**
     * AuthData constructor.
     *
     * @param int|null $id
     * @param string|null $name
     * @param string|null $email
     * @param string|null $password
     * @param string|null $accessToken
     */
    public function __construct(
        public ?string $id = null,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?string $accessToken = null,
    ) {
    }

    /**
     * Get authorization header of the authenticated user.
     * 
     * @return array
     */
    public function getAuthorizationHeader(): array {
        return [
            'Authorization' => "Bearer {$this->accessToken}"
        ];
    }
}