<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Core\Ports\UuidGeneratorProvider;
use Core\Ports\TransactionAuthorizerProvider;
use Core\Ports\DBTransactionProvider;
use Core\Ports\NotificationProvider;
use App\Adapters\UuidGenerator;
use App\Adapters\DBTransactionLaravel;
use App\Adapters\Gateways\NotificationGateway;
use App\Adapters\Gateways\TransactionAuthorizerGateway;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UuidGeneratorProvider::class, UuidGenerator::class);
        $this->app->bind(DBTransactionProvider::class, DBTransactionLaravel::class);
        $this->app->bind(TransactionAuthorizerProvider::class, TransactionAuthorizerGateway::class);
        $this->app->bind(NotificationProvider::class, NotificationGateway::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
