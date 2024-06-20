<?php

namespace Core\DTOs;

use Core\Traits\UsesToArray;

class NotificationDTO
{
    use UsesToArray;

    public function __construct(
        public readonly string $id,
        public readonly int    $person_id,
        public readonly ?int    $type,
        public readonly ?string $message,
        public readonly ?bool   $is_sent,
        public readonly ?string $sent_at,
        public readonly ?string $created_at
    ) {
    }
}
