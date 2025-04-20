<?php

namespace teguh02\ApiResponse;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use teguh02\ApiResponse\Commands\ApiResponseCommand;

class ApiResponseServiceProvider extends PackageServiceProvider
{
    public function register()
    {
        // Register the services
        $this->app->bind('api-response', function() {
            return new ApiResponseBuilder(); 
        });

        // Register package helpers
        foreach (['Package.php'] as $value) {
            require_once __DIR__ . '/Helpers/' . $value;
        }

        parent::register();
    }

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
