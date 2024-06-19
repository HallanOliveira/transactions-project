<?php

namespace App\Adapters;

use Core\Ports\DBTransactionProvider;

class DBTransactionFake implements DBTransactionProvider
{
    public function beginTransaction(): void
    {
        // do nothing
    }

    public function commit(): void
    {
        // do nothing
    }

    public function rollback(): void
    {
        // do nothing
    }
}
