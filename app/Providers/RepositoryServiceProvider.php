<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Core\Ports\PersonRepository as PersonRepositoryInterface;
use Core\Ports\TransactionRepository as TransactionRepositoryInterface;
use Core\Ports\WalletRepository as WalletRepositoryInterface;
use App\Repositories\PersonRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\WalletRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PersonRepositoryInterface::class, PersonRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class,TransactionRepository::class);
        $this->app->bind(WalletRepositoryInterface::class, WalletRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
