<?php

namespace App\Service;

use App\Exceptions\CreateResourceException;
use App\Exceptions\DeleteResourceException;
use App\Exceptions\UpdateResourceException;
use App\Repository\Merchant\MerchantThingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

readonly class MerchantThingService
{

    public function __construct(
        private MerchantThingRepositoryInterface $merchantThingRepository
    )
    {
    }

    public function getAll(): Collection
    {
        Log::info("모든 상품 조회", request()->all());
        return $this->merchantThingRepository->all();
    }

    public function storeThing(array $data): int
    {
        Log::info("상품 저장", request()->all());
        $thing = $this->merchantThingRepository->create($data);

        if (!$thing) {
            throw new CreateResourceException();
        }

        return $thing->id;
    }

    public function findThing(int $id): Model
    {
        Log::info("상품 조회", request()->all());
        $thing = $this->merchantThingRepository->find($id);

        if (!$thing) {
            throw new ModelNotFoundException();
        }

        return $thing;
    }

    public function updateThing(int $id, array $data): int
    {
        Log::info("상품 수정", request()->all());
        $update = $this->merchantThingRepository->update($id, $data);

        if (!$update) {
            throw new UpdateResourceException();
        }

        return $update;
    }

    public function deleteThing(int $id): mixed
    {
        Log::info("상품 삭제", request()->all());
        $delete = $this->merchantThingRepository->delete($id);

        if (!$delete) {
            throw new DeleteResourceException();
        }

        return $delete;
    }

}