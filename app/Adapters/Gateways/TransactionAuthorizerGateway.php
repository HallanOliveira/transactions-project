<?php

namespace App\Adapters\Gateways;

use Core\Ports\TransactionAuthorizerProvider;
use Illuminate\Http\Client\Factory as HttpClient;

class TransactionAuthorizerGateway implements TransactionAuthorizerProvider
{
    private string $url;

    public function __construct(private readonly HttpClient $http)
    {
        $this->url = config('services.transaction_authorizer.url');
    }

    public function execute(): bool
    {
        $response = $this->http->get($this->url);
        return $response->status() === 200 && $response->json('status') === 'success';
    }
}
