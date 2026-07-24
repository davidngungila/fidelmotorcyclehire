<?php

declare(strict_types=1);

namespace App\Providers;

use App\Contracts\GoogleSheetRepositoryInterface;
use App\Models\User;
use App\Policies\DepositPolicy;
use App\Policies\InvestmentPolicy;
use App\Policies\LoanPolicy;
use App\Policies\MemberPolicy;
use App\Policies\SavingsPolicy;
use App\Policies\SwfPolicy;
use App\Policies\UserPolicy;
use App\Repositories\GoogleSheetRepository;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(GoogleSheetRepositoryInterface::class, GoogleSheetRepository::class);
    }

    public function boot(): void
    {
        Gate::policy('member', MemberPolicy::class);
        Gate::policy('loan', LoanPolicy::class);
        Gate::policy('savings', SavingsPolicy::class);
        Gate::policy('deposit', DepositPolicy::class);
        Gate::policy('swf', SwfPolicy::class);
        Gate::policy('investment', InvestmentPolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        Gate::define('admin-only', function (User $user): bool {
            return $user->isAdmin();
        });

        Gate::define('member-only', function (User $user): bool {
            return $user->isMember();
        });

        Gate::define('view-member-data', function (User $user, string $memberNumber): bool {
            return $user->isAdmin() || $user->member_number === $memberNumber;
        });
    }
}
