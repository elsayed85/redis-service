<?php

namespace Elsayed85\RedisService;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Elsayed85\RedisService\Commands\RedisServiceCommand;

class RedisServiceServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('redis-service')
            ->hasConfigFile();
    }
}
