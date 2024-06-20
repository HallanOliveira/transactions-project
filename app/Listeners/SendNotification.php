<?php

namespace App\Listeners;

use App\Events\TransferCompleted;
use Core\UseCases\SendNotificationTransfer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Throwable;

class SendNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct(
        private readonly SendNotificationTransfer $sendNotificationTransfer
    )
    {
    }

    /**
     * Handle the event.
     */
    public function handle(TransferCompleted $event): void
    {
        $this->sendNotificationTransfer->execute($event->transactionDTO);
    }

    /**
     * Handle a job failure.
     */
    public function failed(TransferCompleted $event, Throwable $exception): void
    {
        // ...
    }
}
