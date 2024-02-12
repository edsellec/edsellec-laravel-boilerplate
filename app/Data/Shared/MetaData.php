<?php

namespace App\Data\Shared;

use Spatie\LaravelData\Data;

/**
 * Class MetaData
 *
 * @package App\Data\Shared
 *
 * Represents metadata for queries and data manipulation, such as search criteria, relations, columns, sorting, and pagination.
 *
 * @property string $search Search criteria.
 * @property array $relations List of relations to be eager-loaded.
 * @property array $columns List of columns to be selected.
 * @property string $groupBy Column to group the results by.
 * @property string $sortBy Column to sort the results by.
 * @property string $sortDirection Sort direction (asc or desc).
 * @property int $currentPage Current page for pagination.
 * @property int $perPage Number of items per page for pagination.
 * @property int $offset Offset for pagination.
 * @property bool $isExact Whether to perform an exact match in search criteria.
 */
class MetaData extends Data
{
    /**
     * MetaData constructor.
     *
     * @param string $search Search criteria.
     * @param array $relations List of relations to be eager-loaded.
     * @param array $columns List of columns to be selected.
     * @param string $groupBy Column to group the results by.
     * @param string $sortBy Column to sort the results by.
     * @param string $sortDirection Sort direction (asc or desc).
     * @param int $currentPage Current page for pagination.
     * @param int $perPage Number of items per page for pagination.
     * @param int $offset Offset for pagination.
     * @param bool $isExact Whether to perform an exact match in search criteria.
     */
    public function __construct(
        public string $search = '',
        public array $relations = [],
        public array $columns = [],
        public string $groupBy = '',
        public string $sortBy = '',
        public string $sortDirection = '',
        public int $currentPage = 0,
        public int $perPage = 0,
        public int $offset = 0,
        public bool $isExact = false,
    ) {
    }
}
