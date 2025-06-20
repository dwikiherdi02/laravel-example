<?php

namespace App\Console\Commands\Email;

use App\Services\EmailService;
use Illuminate\Console\Command;
use Log;

class FetchCredits extends Command
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
    protected $signature = 'email:fetch-credits';

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
        $this->info('Fetching credit transactions...');
        $this->emailService->fetchCredits();
        $this->info('Fetching credit transactions success...');
        /* try {
            $this->info('Fetching credit transactions...');
            $this->emailService->fetchCredits();
            $this->info('Fetching credit transactions success...');
        } catch (\Exception $e) {
            Log::error('Error fetching credit transactions: ' . $e->getMessage(), [
                'exception' => $e,
            ]);
            $this->error('An error occurred while fetching credit transactions: ' . $e->getMessage());
        } */
    }
}
