<?php

namespace App\Providers;

use App\Services\CommissionService;
use App\Services\CommissionServiceInterface;
use App\Services\OperationTypeStrategies\OperationTypeStrategyInterface;
use App\Services\OperationTypeStrategies\DepositStrategy;
use App\Services\OperationTypeStrategies\WithdrawStrategy;
use App\Services\UserStrategies\BusinessUser;
use App\Services\UserStrategies\PrivateUser;
use App\Services\UserStrategies\UserStrategyInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->tag([WithdrawStrategy::class, DepositStrategy::class], OperationTypeStrategyInterface::class);
        $this->app->tag([BusinessUser::class, PrivateUser::class], UserStrategyInterface::class);

        $this->app->bind(
            WithdrawStrategy::class,
            function ($app) {
                return new WithdrawStrategy($app->tagged(UserStrategyInterface::class));
            }
        );

        $this->app->bind(
            CommissionServiceInterface::class,
            function ($app) {
                return new CommissionService($app->tagged(OperationTypeStrategyInterface::class));
            }
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
