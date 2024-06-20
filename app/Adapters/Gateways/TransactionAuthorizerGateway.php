<?php

namespace App\Adapters\Gateways;

use Core\Ports\TransactionAuthorizerProvider;
use Illuminate\Support\Facades\Http;

class TransactionAuthorizerGateway implements TransactionAuthorizerProvider
{
    private string $url;

    public function __construct()
    {
        $this->url = config('services.transaction_authorizer.url');
    }

    public function execute(): bool
    {
        $response = Http::get($this->url);
        return $response->status() === 200 && $response->json('status') === 'success';
    }
}
