<?php

namespace Core\Ports;

use Core\Entities\Notification;

interface NotificationRepository
{
    public function get(string $id): ?Notification;
    public function save(Notification $notification): ?bool;
}
