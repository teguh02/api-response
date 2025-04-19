<?php

namespace teguh02\ApiResponse;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use teguh02\ApiResponse\Commands\ApiResponseCommand;

class ApiResponseServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('api-response')
            ->hasConfigFile()
            ->hasCommand(ApiResponseCommand::class);
    }
}
