<?php

namespace App\Providers;

use App\Repository\Store\MerchantRepository;
use App\Repository\Store\MerchantRepositoryInterface;
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
