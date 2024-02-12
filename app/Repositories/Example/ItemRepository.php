<?php

namespace App\Repositories\Example;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Data\Example\ItemData;
use App\Data\Shared\GenericData;
use App\Models\Item;

class ItemRepository
{
    /**
     * Get paginated items from the repository.
     *
     * @param GenericData $data
     * 
     * @return LengthAwarePaginator
     */
    public function get(GenericData $data): LengthAwarePaginator
    {
        $relations = $data->metaData->relations;
        $items = Item::with($relations);

        return $items->orderBy(
            $data->metaData->sortBy,
            $data->metaData->sortDirection
        )->paginate($data->metaData->perPage);
    }

    /**
     * Saves item to the repository.
     *
     * @param ItemData $itemData
     * @param Item|null $item
     * 
     * @return Item|null
     */
    public function save(ItemData $itemData, ?Item $item = null): ?Item
    {
        $item ??= new Item();
        $item->name = $itemData->name;
        $item->status = $itemData->status;

        if (!empty($item)) {
            $item->touch();
        }

        $item->save();
        return $item;
    }

    /**
     * Find item from the repository by id.
     *
     * @param string $id
     * @param array $relations
     * 
     * @return Item|null
     */
    public function find(string $id, array $relations = []): ?Item
    {
        return Item::with($relations)->firstWhere('id', $id);
    }

    /**
     * Check if item exists from the repository by id.
     *
     * @param string $id
     * 
     * @return bool
     */
    public function exists(string $id): bool
    {
        return Item::where('id', $id)->exists();
    }

    /**
     * Delete item from the repository.
     *
     * @param string $id
     * 
     * @return bool
     */
    public function delete(string $id): bool
    {
        $item = $this->find($id);
        if ($item) {
            return (bool) $item->delete();
        }

        return false;
    }
}
