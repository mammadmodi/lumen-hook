<?php

namespace App\Providers;

use Cron\CronExpression;
use Illuminate\Support\Facades\Validator;
use App\Repositories\Hooks\HookRepositoryInterface;
use App\Repositories\Hooks\SqlHookRepository;
use App\Repositories\Users\SqlUserRepository;
use App\Repositories\Users\UserRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('cron', function ($attribute, $value, $parameters) {
            return CronExpression::isValidExpression($value);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepositoryInterface::class, function () {
            return new SqlUserRepository();
        });

        $this->app->bind(HookRepositoryInterface::class, function () {
            return new SqlHookRepository();
        });
    }
}
