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

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDocumentNumber()
    {
        return $this->document_number;
    }

    public function getDocumentType()
    {
        return $this->document_type;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getWallet()
    {
        return $this->wallet;
    }
}
