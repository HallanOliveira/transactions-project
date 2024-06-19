<?php

namespace App\Adapters;

use Core\Ports\DBTransactionProvider;
use Illuminate\Support\Facades\DB;

class DBTransactionFake implements DBTransactionProvider
{
    public function beginTransaction(): void
    {
        DB::beginTransaction();
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
