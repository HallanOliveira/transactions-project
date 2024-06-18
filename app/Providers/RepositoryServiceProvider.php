<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Core\Ports\PersonRepository as PersonRepositoryInterface;
use App\Repositories\PersonRepository;
use Core\Ports\TransactionRepository as TransactionRepositoryInterface;
use App\Repositories\TransactionRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(PersonRepositoryInterface::class,PersonRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class,TransactionRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
