<?php

namespace Teguh Rijanandi\ApiResponse;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Teguh Rijanandi\ApiResponse\Commands\ApiResponseCommand;

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
            ->hasViews()
            ->hasMigration('create_api_response_table')
            ->hasCommand(ApiResponseCommand::class);
    }
}
