<?php

namespace Core\Entities;

use Core\Enums\PersonDocumentType;
use Core\Exceptions\DataNotFoundException;
use Core\Exceptions\PersonTypeInvalidException;
use Core\Entities\Wallet;

class Person
{
    public function __construct(
        public readonly int    $id,
        public readonly string $name,
        public readonly string $document_number,
        public readonly int    $document_type,
        public readonly string $created_at,
        public readonly ?Wallet $wallet
    ) {
    }

    public function isDealerType(): bool
    {
        return $this->document_type === PersonDocumentType::CNPJ->value;
    }

    public function transfer(Person $destinationPerson, $amount): void
    {
        if (! $this->wallet instanceof Wallet) {
            throw new DataNotFoundException('Origin Wallet not found.');
        }
        if (! $destinationPerson->wallet instanceof Wallet) {
            throw new DataNotFoundException('Destination Wallet not found.');
        }
        if ($this->isDealerType()) {
            throw new PersonTypeInvalidException('Dealers cannot transfer money');
        }

        if ($this->id === $destinationPerson->id) {
            throw new DataNotFoundException('The payer and payee cannot be the same');
        }

        if ($this->document_type === $destinationPerson->document_type) {
            throw new PersonTypeInvalidException('The payer and payee must be different types');
        }
    }
}
