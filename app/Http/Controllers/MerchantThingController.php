<?php

namespace App\Http\Controllers;

use App\Http\Requests\Merchant\StoreMerchantThingRequest;
use App\Http\Requests\Merchant\UpdateMerchantThingRequest;
use App\Service\MerchantThingService;
use Illuminate\Http\JsonResponse;

class MerchantThingController extends Controller
{
    public function __construct(
        private readonly MerchantThingService $merchantThingService
    )
    {
    }

    public function index(): JsonResponse
    {
        $things = $this->merchantThingService->getAll();
        return response()->json([
            'result' => 'success',
            'data' => [
                'things' => $things,
            ],
        ]);
    }

    public function store(StoreMerchantThingRequest $request): JsonResponse
    {
        $data = $request->validated();
        $thing = $this->merchantThingService->storeThing($data);

        return response()->json([
            'result' => 'success',
            'data' => [
                'thingId' => $thing,
            ],
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $thing = $this->merchantThingService->findThing($id);

        return response()->json([
            'result' => 'success',
            'data' => [
                'thing' => $thing,
            ],
        ]);
    }

    public function update(UpdateMerchantThingRequest $request, int $id): JsonResponse
    {
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException();
        }

        $data = $request->validated();
        $this->merchantThingService->updateThing($id, $data);

        return response()->json([
            'result' => 'success',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException();
        }

        $this->merchantThingService->deleteThing($id);

        return response()->json([
            'result' => 'success',
        ]);
    }
}
