<?php

namespace App\Repository\Store;

use App\Models\Merchant;
use App\Repository\BaseRepository;

class MerchantRepository extends BaseRepository implements MerchantRepositoryInterface
{

    public function __construct()
    {
        $this->model = new Merchant();
    }
}