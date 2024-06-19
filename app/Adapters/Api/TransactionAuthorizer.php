<?php

namespace App\Adapters\Api;

use Core\Ports\TransactionAuthorizerProvider;
use Illuminate\Support\Facades\Http;

class TransactionAuthorizer implements TransactionAuthorizerProvider
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
