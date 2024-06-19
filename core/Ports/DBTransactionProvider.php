<?php

namespace Core\Ports;

interface DBTransactionProvider
{
    public function beginTransaction(): void;
    public function commit(): void;
    public function rollback(): void;
}
