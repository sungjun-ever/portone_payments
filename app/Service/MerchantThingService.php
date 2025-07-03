<?php

namespace App\Service;

use App\Exceptions\CreateResourceException;
use App\Exceptions\DeleteResourceException;
use App\Exceptions\UpdateResourceException;
use App\Repository\Merchant\MerchantThingRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

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

        return DB::transaction(function () use ($data) {
            $thing = $this->merchantThingRepository->create($data);

            if (!$thing) {
                throw new CreateResourceException();
            }

            //물건 수량 생성
            Redis::set("merchant_thing_stock:$thing->id", $data['stock']);

            return $thing->id;
        });

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

        return DB::transaction(function () use ($id, $data) {
            $update = $this->merchantThingRepository->update($id, $data);

            if (!$update) {
                throw new UpdateResourceException();
            }

            // 수량이 있는 경우 값 변경
            if (isset($data['stock'])) {
                Redis::set("merchant_thing_stock:{$id}", $data['stock']);
            }

            return $update;
        });
    }

    public function deleteThing(int $id): mixed
    {
        Log::info("상품 삭제", request()->all());

        return DB::transaction(function () use ($id) {
            $delete = $this->merchantThingRepository->delete($id);

            if (!$delete) {
                throw new DeleteResourceException();
            }

            // 상품 삭제 시에 수량 정보 삭제
            Redis::del("merchant_thing_stock:{$id}");
            return $delete;
        });

    }

}