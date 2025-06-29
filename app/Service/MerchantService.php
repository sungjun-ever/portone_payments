<?php

namespace App\Service;

use App\Exceptions\CreateResourceException;
use App\Models\Merchant;
use App\Repository\Store\MerchantRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

readonly class MerchantService
{

    public function __construct(
        private MerchantRepositoryInterface $merchantRepository
    )
    {
    }

    public function getAll(): Collection
    {
        Log::info(
            request()->distinction .
            "-> action" . "getAllMerchants"
        );

        return $this->merchantRepository->all();
    }

    /**
     * 상점 저장
     * @param array $data
     * @return int
     * @throws CreateResourceException
     */
    public function storeMerchant(array $data): int
    {
        Log::info(
            request()->distinction .
            "-> action" . "storeMerchant"
        );

        $create = $this->merchantRepository->create($data);

        if (!$create) {
            throw new CreateResourceException();
        }

        return $create->id;
    }
    
    public function findMerchant(int $id): Model
    {
        $merchant = $this->merchantRepository->find($id);

        if (!$merchant) {
            throw new ModelNotFoundException();
        }

        return $merchant;
    }
}