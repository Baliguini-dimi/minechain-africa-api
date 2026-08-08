<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\UserRepositoryInterface::class,
            \App\Repositories\UserRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\OrganizationRepositoryInterface::class,
            \App\Repositories\OrganizationRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\SourceRepositoryInterface::class,
            \App\Repositories\SourceRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\ResourceTypeRepositoryInterface::class,
            \App\Repositories\ResourceTypeRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\LotRepositoryInterface::class,
            \App\Repositories\LotRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\CheckpointRepositoryInterface::class,
            \App\Repositories\CheckpointRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\CheckpointControlRepositoryInterface::class,
            \App\Repositories\CheckpointControlRepository::class
        );

        $this->app->bind(
            \App\Repositories\Contracts\AnomalyRepositoryInterface::class,
            \App\Repositories\AnomalyRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
