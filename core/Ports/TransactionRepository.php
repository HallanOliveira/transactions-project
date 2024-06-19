<?php

namespace Core\Ports;

use Core\Entities\Transaction;

interface TransactionRepository
{
    public function get(int $id): ?Transaction;
    public function save(Transaction $transaction): ?bool;
}

