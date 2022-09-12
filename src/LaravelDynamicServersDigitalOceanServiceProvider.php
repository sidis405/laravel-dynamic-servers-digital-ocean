<?php

namespace Sidis405\LaravelDynamicServersDigitalOcean;

use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelDynamicServersDigitalOceanServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-dynamic-servers-digital-ocean')
            ->publishesServiceProvider('DigitalOceanEventServiceProvider')
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->copyAndRegisterServiceProviderInApp()
                    ->endWith(function (InstallCommand $installCommand) {
                        $installCommand->line('');
                        $installCommand->info("We've added app\Providers\DigitalOceanEventServiceProvider to your project.");
                        $installCommand->info('Feel free to customize it to your needs.');
                    });
            });
    }
}
