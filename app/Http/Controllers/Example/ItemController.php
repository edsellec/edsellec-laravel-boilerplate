<?php

namespace App\Http\Controllers\Example;

use Illuminate\Routing\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Requests\GenericRequest;
use App\Http\Requests\Example\ItemRequest;
use App\Http\Resources\Example\ItemResource;
use App\Helpers\ResponseHelper;
use App\Services\Example\ItemService;

class ItemController extends Controller
{
    private ItemService $itemService;

    /**
     * ItemController constructor.
     *
     * @param ItemService $itemService
     */
    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }
    
    /**
     * Display a listing of the items.
     *
     * @param GenericRequest $request
     * 
     * @return JsonResponse|JsonResource
     */
    public function index(GenericRequest $request): JsonResponse|JsonResource
    {
        $data = GenericRequest::createFrom($request)->toData();
        $responseData = $this->itemService->get($data);

        if ($responseData->isError()) {
            return ResponseHelper::error($responseData->getMessage());
        }

        return ResponseHelper::resource(ItemResource::class, $responseData->getData());
    }

    /**
     * Store a newly created item in storage.
     *
     * @param ItemRequest $request
     * 
     * @return JsonResponse|JsonResource
     */
    public function store(ItemRequest $request): JsonResponse|JsonResource
    {
        $responseData = $this->itemService->create($request->toData());

        if ($responseData->isError()) {
            return ResponseHelper::error($responseData->getMessage());
        }

        return ResponseHelper::resource(ItemResource::class, $responseData->getData());
    }

    /**
     * Display the item.
     *
     * @param GenericRequest $request
     * @param string $id
     * 
     * @return JsonResponse|JsonResource
     */
    public function show(GenericRequest $request, string $id): JsonResponse|JsonResource
    {
        $data = GenericRequest::createFrom($request)->toData();
        $relations = $data->metaData->relations;
        $responseData = $this->itemService->find($id, $relations);

        if ($responseData->isError()) {
            return ResponseHelper::error($responseData->getMessage());
        }

        return ResponseHelper::resource(ItemResource::class, $responseData->getData());
    }

    /**
     * Update the item in storage.
     *
     * @param ItemRequest $request
     * @param string $id
     * 
     * @return JsonResponse|JsonResource
     */
    public function update(ItemRequest $request, string $id): JsonResponse|JsonResource
    {
        $itemData = $request->toData();
        $itemData->id = $id;
        $responseData = $this->itemService->update($itemData);

        if ($responseData->isError()) {
            return ResponseHelper::error($responseData->getMessage());
        }

        return ResponseHelper::resource(ItemResource::class, $responseData->getData());
    }

    /**
     * Remove the item from storage.
     *
     * @param string $id
     * 
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse|JsonResource
    {
        $responseData = $this->itemService->delete($id);

        if ($responseData->isError()) {
            return ResponseHelper::error($responseData->getMessage());
        }

        return ResponseHelper::success($responseData->getMessage());
    }
}
