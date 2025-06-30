<?php

namespace App\Providers;

use App\Repository\Merchant\MerchantRepository;
use App\Repository\Merchant\MerchantRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class InterfaceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(MerchantRepositoryInterface::class, MerchantRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
