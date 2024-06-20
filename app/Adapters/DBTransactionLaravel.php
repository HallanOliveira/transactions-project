<?php

namespace App\Adapters;

use Core\Ports\DBTransactionProvider;
use Illuminate\Database\DatabaseManager;

class DBTransactionLaravel implements DBTransactionProvider
{
    public function __construct(private DatabaseManager $dataBase)
    {
        $this->dataBase = $dataBase;
    }

    public function beginTransaction(): void
    {
        $this->dataBase->connection()->beginTransaction();
    }

    public function commit(): void
    {
        $this->dataBase->connection()->commit();
    }

    public function rollback(): void
    {
        $this->dataBase->connection()->rollBack();
    }
}
