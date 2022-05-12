<?php

namespace App\Providers;

use App\Contracts\BookConstract;
use App\Contracts\UserContract;
use App\Services\BookService;
use App\Services\UserService;
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
        $this->app->singleton(UserContract::class, UserService::class);
        $this->app->singleton(BookConstract::class, BookService::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
