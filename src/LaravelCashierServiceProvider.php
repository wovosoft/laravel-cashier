<?php

namespace Wovosoft\LaravelCashier;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Wovosoft\LaravelCashier\Commands\LaravelCashierCommand;

class LaravelCashierServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-cashier')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigrations()
            ->hasCommand(LaravelCashierCommand::class);
    }
}
