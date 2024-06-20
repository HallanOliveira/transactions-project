<?php

namespace App\Adapters\Events;

use App\Events\TransferCompleted;
use Core\Ports\TransferEventDispatcher;
use Core\DTOs\TransactionDTO;
use Illuminate\Contracts\Events\Dispatcher;

class TransferEventDipatcher implements TransferEventDispatcher
{
    public function __construct(private Dispatcher $eventDispatcher)
    {
    }

    public function dispatch(TransactionDTO $transactionDTO): void
    {
        $this->eventDispatcher->dispatch(new TransferCompleted($transactionDTO));
    }
}
