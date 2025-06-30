<?php

namespace App\Repository\Merchant;

use App\Models\MerchantThing;
use App\Repository\BaseRepository;

class MerchantThingRepository extends BaseRepository implements MerchantThingRepositoryInterface
{

    public function __construct()
    {
        $this->model = new MerchantThing();
    }
}