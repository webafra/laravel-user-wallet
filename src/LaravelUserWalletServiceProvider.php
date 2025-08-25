<?php

namespace Webafra\LaravelUserWallet;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Webafra\LaravelUserWallet\Commands\LaravelUserWalletCommand;

class LaravelUserWalletServiceProvider extends PackageServiceProvider
{

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-user-wallet')
            ->hasConfigFile()
            ->hasViews()
             ->hasMigrations(
                'create_wallets_logs_table',
                'create_wallets_table',
                'add_notes_and_reference_columns_to_wallets_logs_table'
            )
            ->hasCommand(LaravelUserWalletCommand::class);
    }

     public function bootingPackage()
    {
        $this->publishes([
            __DIR__.'/../Enums/' => app_path('Enums'),
        ], 'user-wallets');

        $this->publishes([
            __DIR__.'/../config/user-wallet.php' => config_path('user-wallet.php'),
        ], 'config');
    }

    public function registeringPackage()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/user-wallet.php', 'user-wallet');
    }
}
