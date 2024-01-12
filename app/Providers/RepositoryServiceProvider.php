<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Accounting\Domain\Repository\BaseRepository;
use Src\Accounting\Domain\Repository\BusinessRepository;
use Src\Accounting\Domain\Repository\ClientRepository;
use Src\Accounting\Domain\Repository\Interfaces\BaseRepositoryInterface;
use Src\Accounting\Domain\Repository\Interfaces\BusinessRepositoryInterface;
use Src\Accounting\Domain\Repository\Interfaces\ClientRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            BaseRepositoryInterface::class,
            BaseRepository::class
        );
        $this->app->bind(
            ClientRepositoryInterface::class,
            ClientRepository::class
        );
        $this->app->bind(
            BusinessRepositoryInterface::class,
            BusinessRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
