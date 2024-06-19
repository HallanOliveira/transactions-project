<?php

namespace App\Adapters;

use Core\Ports\DBTransactionProvider;
use Illuminate\Support\Facades\DB;

class DBTransactionLaravel implements DBTransactionProvider
{
    public function beginTransaction(): void
    {
        DB::beginTransaction();
    }

    public function commit(): void
    {
        DB::commit();
    }

    public function rollback(): void
    {
        DB::rollBack();
    }
}
