<?php

namespace App\Providers;

use App\Events\BalanceCalculationRequested;
use App\Listeners\HandleBalanceCalculation;
use App\Models\Contribution;
use App\Models\DuesMonth;
use App\Models\DuesPayment;
use App\Models\DuesPaymentDetail;
use App\Models\Imap;
use App\Models\Menu;
use App\Models\MenuGroup;
use App\Models\MenuRole;
use App\Models\Resident;
use App\Models\ResidentPoint;
use App\Models\Role;
use App\Models\SystemBalance;
use App\Models\TextTemplate;
use App\Models\Transaction;
use App\Models\TransactionMethod;
use App\Models\TransactionType;
use App\Models\User;
use App\Repositories\ContributionRepository;
use App\Repositories\DuesMonthRepository;
use App\Repositories\DuesPaymentDetailRepository;
use App\Repositories\DuesPaymentRepository;
use App\Repositories\ImapRepository;
use App\Repositories\MenuGroupRepository;
use App\Repositories\MenuRepository;
use App\Repositories\MenuRoleRepository;
use App\Repositories\ResidentPointRepository;
use App\Repositories\ResidentRepository;
use App\Repositories\RoleRepository;
use App\Repositories\SystemBalanceRepository;
use App\Repositories\TextTemplateRepository;
use App\Repositories\TransactionMethodRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\TransactionTypeRepository;
use App\Repositories\UserRepository;
use App\Services\ComponentService;
use App\Services\ContributionService;
use App\Services\DuesMonthService;
use App\Services\DuesPaymentService;
use App\Services\ImapService;
use App\Services\MenuRoleService;
use App\Services\ResidentService;
use App\Services\SystemBalanceService;
use App\Services\TextTemplateService;
use App\Services\TransactionService;
use App\Services\UserService;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Webklex\PHPIMAP\ClientManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerLibraries();

        $this->registerRepositories();

        $this->registerServices();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(
            BalanceCalculationRequested::class,
            HandleBalanceCalculation::class,
        );
    }

    protected function registerLibraries()
    {
        $this->app->bind(\App\Libraries\Imap::class, function () {
            return new \App\Libraries\Imap(new ClientManager(), new ImapRepository(new Imap()));
        });
    }

    protected function registerRepositories()
    {
        $repositories = [
            ContributionRepository::class => Contribution::class,
            DuesMonthRepository::class => DuesMonth::class,
            DuesPaymentDetailRepository::class => DuesPaymentDetail::class,
            DuesPaymentRepository::class => DuesPayment::class,
            ImapRepository::class => Imap::class,
            MenuGroupRepository::class => MenuGroup::class,
            MenuRepository::class => Menu::class,
            MenuRoleRepository::class => MenuRole::class,
            ResidentRepository::class => Resident::class,
            RoleRepository::class => Role::class,
            SystemBalanceRepository::class => SystemBalance::class,
            TextTemplateRepository::class => TextTemplate::class,
            TransactionTypeRepository::class => TransactionType::class,
            TransactionMethodRepository::class => TransactionMethod::class,
            TransactionRepository::class => Transaction::class,
            ResidentPointRepository::class => ResidentPoint::class,
            UserRepository::class => User::class,
        ];

        foreach ($repositories as $repo => $model) {
            $this->app->bind($repo, function ($app) use ($repo, $model) {
                // return new $repo(new $model());
                return new $repo($app->make($model));
            });
        }
    }

    protected function registerServices()
    {
        $services = [
            ComponentService::class => [
                MenuGroupRepository::class,
                MenuRepository::class,
                RoleRepository::class,
                ResidentRepository::class,
                TransactionTypeRepository::class,
                ContributionRepository::class,
                TransactionMethodRepository::class,
            ],
            DuesMonthService::class => [
                DuesMonthRepository::class,
                DuesPaymentDetailRepository::class,
                DuesPaymentRepository::class,
                ResidentRepository::class,
            ],
            DuesPaymentService::class => [
                DuesPaymentRepository::class,
            ],
            ContributionService::class => [
                ContributionRepository::class,
            ],
            ImapService::class => [
                \App\Libraries\Imap::class,
                ImapRepository::class,
            ],
            MenuRoleService::class => [
                MenuRoleRepository::class,
            ],
            ResidentService::class => [
                ResidentRepository::class,
                UserRepository::class,
            ],
            SystemBalanceService::class => [
                SystemBalanceRepository::class,
                TransactionRepository::class,
                ResidentPointRepository::class,
            ],
            TextTemplateService::class => [
                TextTemplateRepository::class,
                \App\Libraries\Imap::class,
            ],
            TransactionService::class => [
                TransactionRepository::class,
                DuesPaymentRepository::class,
            ],
            UserService::class => [
                UserRepository::class,
            ],
        ];

        foreach ($services as $service => $dependencies) {
            $this->app->bind($service, function ($app) use ($service, $dependencies) {
                $resolved = array_map(fn($dep) => $app->make($dep), $dependencies);
                return new $service(...$resolved);
            });
        }
    }
}
