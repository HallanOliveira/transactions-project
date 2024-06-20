<?php

namespace App\Adapters\Gateways;

use Core\DTOs\NotificationDTO;
use Illuminate\Support\Facades\Http;
use Core\Ports\NotificationProvider;

class NotificationGateway implements NotificationProvider
{
    private string $url;

    public function __construct()
    {
        $this->url = config('services.notification.url');
    }

    public function send(NotificationDTO $data): bool
    {
        $response = Http::post($this->url);
        return $response->ok() && $response->json()['status'] === 'success';
    }
}
