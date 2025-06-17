<?php

namespace App\Console\Commands;

use App\Services\ImapService;
use App\Services\TextTemplateService;
use App\Services\TransactionService;
use Illuminate\Console\Command;

class FetchPaymentDues extends Command
{

    function __construct(
        protected TextTemplateService $textTemplateService,
        protected TransactionService $transactionService,
    ) {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-payment-dues';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch payment dues from the email body and update payment dues in the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $this->info('Fetching payment dues...');
        $this->textTemplateService->getPaymentDuesEmailBody();
    }
}
