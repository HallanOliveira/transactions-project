<?php

namespace Core\DTOs;

class TransactionDTO
{
    public function __construct(
        public readonly string $id,
        public readonly int    $person_origin_id,
        public readonly ?int   $person_destination_id,
        public readonly string $type,
        public readonly float  $amount
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
