<?php

namespace App\Console\Commands\Email;

use App\Services\EmailService;
use Illuminate\Console\Command;
use Log;


class ProcessUnseenDebits extends Command
{
    function __construct(
        protected EmailService $emailService,
    ) {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:process-unseen-debits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process debits from unseen emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing unseen debit emails...');
        $this->emailService->processUnseenDebits();
        $this->info('Processing unseen debit emails success...');
    }
}
