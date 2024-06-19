<?php

namespace App\Listeners;

use App\Events\TransferCompleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Throwable;

class SendNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     */
    public function handle(TransferCompleted $event): void
    {
        // ...
    }

    /**
     * Handle a job failure.
     */
    public function failed(TransferCompleted $event, Throwable $exception): void
    {
        // ...
    }
}
