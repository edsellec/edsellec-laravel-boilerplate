<?php

namespace App\Data\Example;

use Spatie\LaravelData\Data;
use Illuminate\Support\Carbon;
use App\Constants\StatusConstant;
use App\Data\Shared\MetaData;
use App\Data\Shared\UserData;

/**
 * Class ItemData
 *
 * @package App\Data\Example
 *
 * @property string|null $id
 * @property string|null $name
 * @property string|null $status
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property Carbon|null $deletedAt
 * @property MetaData|null $metaData
 * @property UserData|null $userData
 */
class ItemData extends Data
{
    /**
     * ItemData constructor.
     *
     * @param string|null $id
     * @param string|null $name
     * @param string|null $status
     * @param Carbon|null $createdAt
     * @param Carbon|null $updatedAt
     * @param Carbon|null $deletedAt
     * @param MetaData|null $metaData
     * @param UserData|null $userData
     */
    public function __construct(
        public ?string $id = null,
        public ?string $name = null,
        public ?string $status = StatusConstant::PUBLISHED,
        public ?Carbon $createdAt = null,
        public ?Carbon $updatedAt = null,
        public ?Carbon $deletedAt = null,
        public ?MetaData $metaData = null,
        public ?UserData $userData = null,
    ) {
        $this->metaData = $metaData ?? new MetaData();
        $this->userData = $userData ?? new UserData();
    }
}
