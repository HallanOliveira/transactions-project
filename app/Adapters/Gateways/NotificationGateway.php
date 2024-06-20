<?php
namespace App\Adapters\Gateways;

use Core\DTOs\NotificationDTO;
use Illuminate\Http\Client\Factory as HttpClient;
use Core\Ports\NotificationProvider;

class NotificationGateway implements NotificationProvider
{
    private string $url;

    public function __construct(private readonly HttpClient $http)
    {
        $this->url = config('services.notification.url');
    }

    public function send(NotificationDTO $data): bool
    {
        $response = $this->http->post($this->url, $data->toArray());
        return $response->ok() && $response->json()['status'] === 'success';
    }
}
