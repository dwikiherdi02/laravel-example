<?php

namespace App\Listeners;

use App\Enum\TransactionTypeEnum;
use App\Events\ProcessUnseenRequested;
use App\Services\EmailService;

class HandleProcessUnseen
{
    /**
     * Create the event listener.
     */
    public function __construct(
        protected EmailService $service
    ) {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ProcessUnseenRequested $event): void
    {
        if ($event->transactionType == TransactionTypeEnum::Credit) {
            // Process unseen credits
            $this->service->processUnseenCredits();
        } elseif ($event->transactionType == TransactionTypeEnum::Debit) {
            // Process unseen debits
            $this->service->processUnseenDebits();
        }
    }
}
