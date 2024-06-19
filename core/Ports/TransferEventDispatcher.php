<?php

namespace Core\Ports;

use Core\DTOs\TransactionDTO;

interface TransferEventDispatcher
{
    public function dispatch(TransactionDTO $transactionDTO):void ;
}