<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Accounting\Domain\Repository\BaseRepository;
use Src\Accounting\Domain\Repository\BusinessRepository;
use Src\Accounting\Domain\Repository\ClientRepository;
use Src\Accounting\Domain\Repository\Interfaces\BaseRepositoryInterface;
use Src\Accounting\Domain\Repository\Interfaces\BusinessRepositoryInterface;
use Src\Accounting\Domain\Repository\Interfaces\ClientRepositoryInterface;
use Src\Accounting\Domain\Repository\Interfaces\InventoryRepositoryInterface;
use Src\Accounting\Domain\Repository\Interfaces\InvoiceRepositoryInterface;
use Src\Accounting\Domain\Repository\Interfaces\ReceiptRepositoryInterface;
use Src\Accounting\Domain\Repository\InventoryRepository;
use Src\Accounting\Domain\Repository\InvoiceRepository;
use Src\Accounting\Domain\Repository\ReceiptRepository;
use Src\Wallets\Payments\Domain\Repository\Interfaces\JournalRepositoryInterface;
use Src\Wallets\Payments\Domain\Repository\JournalRepository;

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
        $this->app->bind(
            InvoiceRepositoryInterface::class,
            InvoiceRepository::class
        );
        $this->app->bind(
            InventoryRepositoryInterface::class,
            InventoryRepository::class
        );
        $this->app->bind(
            ReceiptRepositoryInterface::class,
            ReceiptRepository::class
        );

        $this->app->bind(
            JournalRepositoryInterface::class,
            JournalRepository::class
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
