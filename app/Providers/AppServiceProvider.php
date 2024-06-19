<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Core\Ports\UuidGeneratorProvider;
use Core\Ports\TransactionAuthorizerProvider;
use Core\Ports\DBTransactionProvider;
use App\Adapters\UuidGenerator;
use App\Adapters\Api\TransactionAuthorizer;
use App\Adapters\DBTransactionLaravel;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UuidGeneratorProvider::class, UuidGenerator::class);
        $this->app->bind(TransactionAuthorizerProvider::class, TransactionAuthorizer::class);
        $this->app->bind(DBTransactionProvider::class, DBTransactionLaravel::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
