<?php

namespace App\Providers;

use App\Repositories\Hooks\HookRepositoryInterface;
use App\Repositories\Hooks\SqlHookRepository;
use App\Repositories\Users\SqlUserRepository;
use App\Repositories\Users\UserRepositoryInterface;
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
        $this->app->bind(UserRepositoryInterface::class, function () {
            return new SqlUserRepository();
        });

        $this->app->bind(HookRepositoryInterface::class, function () {
            return new SqlHookRepository();
        });
    }
}
