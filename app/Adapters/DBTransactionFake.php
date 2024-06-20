<?php

namespace App\Adapters;

use Core\Ports\DBTransactionProvider;
use Illuminate\Database\DatabaseManager;

class DBTransactionLaravel implements DBTransactionProvider
{
    protected $dataBase;

    public function __construct(DatabaseManager $dataBase)
    {
        $this->dataBase = $dataBase;
    }

    public function beginTransaction(): void
    {
        $this->dataBase->connection()->beginTransaction();
    }

    public function commit(): void
    {
        // nothing to do here
    }

    public function rollback(): void
    {
        $this->dataBase->connection()->rollBack();
    }
}
