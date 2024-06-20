<?php

namespace Core\Ports;

use Core\DTOs\NotificationDTO;

interface NotificationProvider
{
    public function send(NotificationDTO $data): bool;
}
