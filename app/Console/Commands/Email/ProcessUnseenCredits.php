<?php

namespace App\Console\Commands\Email;

use App\Services\EmailService;
use Illuminate\Console\Command;
use Log;


class ProcessUnseenCredits extends Command
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
    protected $signature = 'email:process-unseen-credits';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process credits from unseen emails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing unseen credit emails...');
        $this->emailService->processUnseenCredits();
        $this->info('Processing unseen credit emails success...');
    }
}
