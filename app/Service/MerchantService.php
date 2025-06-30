<?php

namespace App\Service;

use App\Exceptions\CreateResourceException;
use App\Exceptions\DeleteResourceException;
use App\Exceptions\UpdateResourceException;
use App\Models\Merchant;
use App\Repository\Merchant\MerchantRepositoryInterface;
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
        Log::info("모든 상점 조회", request()->all());

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
        Log::info("상점 생성", request()->all());

        $create = $this->merchantRepository->create([
            'name' => $data['name'],
        ]);

        if (!$create) {
            throw new CreateResourceException();
        }

        return $create->id;
    }
    
    public function findMerchant(int $id): Model
    {
        Log::info("상점 조회", request()->all());
        $merchant = $this->merchantRepository->find($id);

        if (!$merchant) {
            throw new ModelNotFoundException();
        }

        return $merchant;
    }

    public function updateMerchant(int $id, array $data): int
    {
        Log::info("상점 수정", request()->all());

        $update = $this->merchantRepository->update($id, [
            'name' => $data['name'],
        ]);

        if (!$update) {
            throw new UpdateResourceException();
        }
        return $update;
    }

    public function deleteMerchant(int $id): mixed
    {
        Log::info("상점 삭제", request()->all());

        $delete = $this->merchantRepository->delete($id);

        if (!$delete) {
            throw new DeleteResourceException();
        }

        return $delete;
    }
}