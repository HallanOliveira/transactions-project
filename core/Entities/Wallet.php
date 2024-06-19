<?php

namespace Core\Entities;

use Core\Exceptions\InsufficientBalanceException;
class Wallet
{
    public function __construct(
        private int    $id,
        private float  $balance,
        private int    $person_id,
        private string $created_at,
    ) {
    }

    public function deposit(float $amount): void
    {
        $this->balance += $amount;
    }

    public function withdraw(float $amount): void
    {
        if ($this->balance < $amount) {
            throw new InsufficientBalanceException('Insufficient balance.');
        }
        $this->balance -= $amount;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPersonId(): int
    {
        return $this->person_id;
    }

    public function getCreatedAt(): string
    {
        return $this->created_at;
    }
}
