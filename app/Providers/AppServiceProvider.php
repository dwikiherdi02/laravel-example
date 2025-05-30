<?php

namespace App\Providers;

use App\Models\TransactionType;
use App\Repositories\TransactionTypeRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TransactionTypeRepository::class, function () {
            return new TransactionTypeRepository(new TransactionType());
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
