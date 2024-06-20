<?php

namespace Core\Ports;

use Core\Entities\Transaction;

interface TransactionRepository
{
    public function get(string $id): ?Transaction;
    public function save(Transaction $transaction): ?bool;
}

