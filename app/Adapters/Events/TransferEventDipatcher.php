<?php

namespace App\Adapters\Events;

use App\Events\TransferCompleted;
use Core\Ports\TransferEventDispatcher;
use Core\DTOs\TransactionDTO;

class TransferEventDipatcher implements TransferEventDispatcher
{
    public function dispatch(TransactionDTO $transactionDTO): void
    {
        TransferCompleted::dispatch($transactionDTO);
    }
}