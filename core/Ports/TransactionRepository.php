<?php

namespace Core\Ports;

use Core\Entities\Transaction;

interface TransactionRepository
{
    public function get(int $id): ?Transaction;
}

