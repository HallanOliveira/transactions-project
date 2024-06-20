<?php

namespace Core\Entities;

use Core\Enums\PersonDocumentType;
use Core\Entities\Wallet;

class Person
{
    public function __construct(
        private int     $id,
        private string  $name,
        private string  $document_number,
        private int     $document_type,
        private ?string $phone,
        private string  $created_at,
        private ?Wallet $wallet
    ) {
    }

    public function isDealerType(): bool
    {
        return $this->document_type === PersonDocumentType::CNPJ->value;
    }

    public function changeDocumentType(PersonDocumentType $type)
    {
        $this->document_type = $type->value;
    }

    public function canPerformTransactions(): bool {
        return ! $this->isDealerType();
    }

    public function hasPhone(): bool
    {
        return ! empty($this->phone);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDocumentNumber(): string
    {
        return $this->document_number;
    }

    public function getDocumentType(): int
    {
        return $this->document_type;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }
}
