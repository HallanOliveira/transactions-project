<?php

namespace App\Adapters\Api;

use Core\Ports\TransactionAuthorizerProvider;

class TransactionAuthorizerFalse implements TransactionAuthorizerProvider
{
    public function execute(): bool
    {
        return false;
    }
}
