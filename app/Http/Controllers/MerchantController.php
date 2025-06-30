<?php

namespace App\Http\Controllers;

use App\Http\Requests\Merchant\StoreMerchantRequest;
use App\Http\Requests\Merchant\UpdateMerchantRequest;
use App\Service\MerchantService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MerchantController extends Controller
{

    public function __construct(
        private readonly MerchantService $merchantService
    )
    {
    }

    public function index(Request $request): JsonResponse
    {
        Log::info($request->distinction . ': get all merchants');
        $merchants = $this->merchantService->getAll();

        return response()->json([
            'result' => 'success',
            'merchants' => $merchants,
        ]);
    }

    public function store(StoreMerchantRequest $request): JsonResponse
    {
        Log::info($request->distinction . 'store merchant request');

        $data = $request->validated();

        $merchantId = $this->merchantService->storeMerchant([
            'name' => $data['name'],
        ]);

        return response()->json([
            'result' => 'success',
            'data' => [
                'merchantId' => $merchantId,
            ],
        ], 201);
    }
    
    public function show(int $id): JsonResponse
    {
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException();
        }

        $merchant = $this->merchantService->findMerchant($id);

        return response()->json([
            'result' => 'success',
            'data' => [
                'merchant' => $merchant,
            ],
        ]);
    }

    public function update(UpdateMerchantRequest $request, int $id): JsonResponse
    {
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException();
        }

        $data = $request->validated();

        $this->merchantService->updateMerchant($id, $data);

        return response()->json([
            'result' => 'success',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        if (!is_numeric($id)) {
            throw new \InvalidArgumentException();
        }

        $this->merchantService->deleteMerchant($id);

        return response()->json([
            'result' => 'success',
        ]);
    }
}
