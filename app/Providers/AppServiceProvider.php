<?php

namespace App\Providers;

use App\Models\Contribution;
use App\Models\Imap;
use App\Models\TextTemplate;
use App\Models\TransactionType;
use App\Repositories\ContributionRepository;
use App\Repositories\ImapRepository;
use App\Repositories\TextTemplateRepository;
use App\Repositories\TransactionTypeRepository;
use Illuminate\Support\ServiceProvider;
use Webklex\PHPIMAP\ClientManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ContributionRepository::class, function () {
            return new ContributionRepository(new Contribution());
        });

        $this->app->bind(TransactionTypeRepository::class, function () {
            return new TransactionTypeRepository(new TransactionType());
        });

        $this->app->bind(TextTemplateRepository::class, function () {
            return new TextTemplateRepository(new TextTemplate());
        });

        $this->app->bind(ImapRepository::class, function () {
            return new ImapRepository(new Imap());
        });

        $this->app->bind(\App\Libraries\Imap::class, function () {
            return new \App\Libraries\Imap(new ClientManager(), new ImapRepository(new Imap()));
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
