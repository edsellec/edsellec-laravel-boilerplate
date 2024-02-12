<?php

namespace App\Services\Example;

use App\Data\Shared\GenericData;
use App\Data\Shared\ResponseData;
use App\Data\Example\ItemData;
use App\Helpers\ErrorHelper;
use App\Repositories\Example\ItemRepository;
use Throwable;

class ItemService
{
    private ItemRepository $itemRepository;

    /**
     * ItemService constructor.
     *
     * @param ItemRepository $itemRepository
     */
    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }
    
    /**
     * Display a listing of the items.
     *
     * @param GenericData $data
     * 
     * @return ResponseData
     */
    public function get(GenericData $data): ResponseData
    {
        try {
            return ResponseData::map(
                $this->itemRepository->get($data)
            );
        } catch (Throwable $e) {
            return ErrorHelper::generateErrorResponse(__CLASS__, __FUNCTION__, $e);
        }
    }

    /**
     * Store a newly created item in storage.
     *
     * @param ItemData $itemData
     * 
     * @return ResponseData
     */
    public function create(ItemData $itemData): ResponseData
    {
        try {
            $item = $this->itemRepository->save($itemData);
            if (empty($item)) {
                return ResponseData::error('Unable to create item. Please check your input and try again.');
            }

            return ResponseData::success('New item created successfully.', $item);
        } catch (Throwable $e) {
            return ErrorHelper::generateErrorResponse(__CLASS__, __FUNCTION__, $e);
        }
    }

    /**
     * Display the item.
     *
     * @param string $id
     * @param array $relations
     * 
     * @return ResponseData
     */
    public function find(string $id, array $relations = []): ResponseData
    {
        try {
            return ResponseData::map(
                $this->itemRepository->find($id, $relations)
            );
        } catch (Throwable $e) {
            return ErrorHelper::generateErrorResponse(__CLASS__, __FUNCTION__, $e);
        }
    }

    /**
     * Update the item in storage.
     *
     * @param ItemData $itemData
     * 
     * @return ResponseData
     */
    public function update(ItemData $itemData): ResponseData
    {
        try {
            $item = $this->itemRepository->find($itemData->id);
            if (empty($item)) {
                return ResponseData::error('The requested item could not be found. Please verify the ID and try again.');
            }

            $item = $this->itemRepository->save($itemData, $item);
            if (empty($item)) {
                return ResponseData::error('Unable to update item. Please check your input and try again.');
            }

            return ResponseData::success('Item updated successfully.', $item);
        } catch (Throwable $e) {
            return ErrorHelper::generateErrorResponse(__CLASS__, __FUNCTION__, $e);
        }
    }

    /**
     * Remove the item from storage.
     *
     * @param string $id
     * 
     * @return ResponseData
     */
    public function delete(string $id): ResponseData
    {
        try {
            $isExists = $this->itemRepository->exists($id);
            if (!$isExists) {
                return ResponseData::error('The requested item could not be found. Please verify the ID and try again.');
            }

            $isDeleted = $this->itemRepository->delete($id);
            if (!$isDeleted) {
                return ResponseData::error('Unable to delete the item. Please ensure that the ID is valid and try again.');
            }

            return ResponseData::success('Item deleted successfully.');
        } catch (Throwable $e) {
            return ErrorHelper::generateErrorResponse(__CLASS__, __FUNCTION__, $e);
        }
    }
}
