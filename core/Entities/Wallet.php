<?php

namespace Core\Entities;

class Wallet
{
    public function __construct(
        public readonly int    $id,
        public readonly float  $balance,
        public readonly string $created_at,
    ) {
    }

    public function deposit(float $amount): void
    {
        $this->balance += $amount;
    }

    public function withdraw(float $amount): void
    {
        $this->balance -= $amount;
    }
}