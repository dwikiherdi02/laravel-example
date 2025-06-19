<?php

namespace App\Console\Commands\Transaction;

use App\Services\EmailService;
use App\Services\TextTemplateService;
use App\Services\TransactionService;
use Illuminate\Console\Command;
use Log;

class FetchCredits extends Command
{

    function __construct(
        protected EmailService $emailService,
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
    protected $signature = 'transaction:fetch-credits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and process credit transactions';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $this->info('Fetching credit transactions...');
            $templates = $this->textTemplateService->getCreditTemplates();
            $this->emailService->generateCreditEmail($templates);
        } catch (\Exception $e) {
            Log::error('Error fetching credit transactions: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            $this->error('An error occurred while fetching credit transactions: ' . $e->getMessage());
        }
    }
}
