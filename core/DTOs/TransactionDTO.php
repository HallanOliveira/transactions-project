<?php

namespace Core\DTOs;

use Core\Traits\UsesToArray;

class TransactionDTO
{
    use UsesToArray;

    public function __construct(
        public readonly int     $person_origin_id,
        public readonly ?int    $person_destination_id,
        public readonly string  $type,
        public readonly float   $amount,
        public readonly ?string $id = null
    ) {
    }
}
