<?php

namespace App\Adapters\Api;

use Core\Ports\TransactionAuthorizerProvider;

class TransactionAuthorizerTrue implements TransactionAuthorizerProvider
{
    public function execute(): bool
    {
        return true;
    }
}
