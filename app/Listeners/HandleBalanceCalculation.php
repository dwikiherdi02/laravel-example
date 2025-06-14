<?php

namespace App\Listeners;

use App\Dto\TransactionDto;
use App\Events\BalanceCalculationRequested;
use App\Services\SystemBalanceService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleBalanceCalculation implements ShouldQueue
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
    public function handle(BalanceCalculationRequested $event): void
    {
        $transaction = TransactionDto::from($event->transaction);
        $this->service->reCalculateBalance($transaction);
    }
}
