<?php

namespace Core\Entities;

use Core\Enums\PersonDocumentType;

class Person
{
    public function __construct(
        private readonly int    $id,
        private readonly string $name,
        private readonly string $document_number,
        private readonly int    $document_type,
        private readonly string $created_at
    ) {
    }

    public function isDealerType(): bool
    {
        return $this->document_type === PersonDocumentType::CNPJ->value;
    }
}
