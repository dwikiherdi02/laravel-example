<?php

namespace App\Listeners;

use App\Dto\TransactionDto;
use App\Events\CancellationBalanceRequested;
use App\Services\SystemBalanceService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleCancellationBalance implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct(
        protected SystemBalanceService $service
    ) {
    }

    /**
     * Handle the event.
     */
    public function handle(CancellationBalanceRequested $event): void
    {
        $transaction = TransactionDto::from($event->transaction);
        $this->service->reCalculateBalanceForCancellation($transaction);
    }
}
