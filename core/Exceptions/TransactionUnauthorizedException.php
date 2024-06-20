<?php

namespace Core\Exceptions;

use Exception;

class TransactionUnauthorizedException extends Exception
{
    public function __construct()
    {
        parent::__construct('Transaction not authorized', 401);
    }
}
