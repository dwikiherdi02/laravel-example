<?php

namespace App\Events;

use App\Enum\TransactionTypeEnum;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProcessUnseenRequested
{
    use Dispatchable, SerializesModels;

    public TransactionTypeEnum $transactionType;

    /**
     * Create a new event instance.
     */
    public function __construct(TransactionTypeEnum $transactionType)
    {
        $this->transactionType = $transactionType;
    }
}
