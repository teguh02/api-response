<?php

namespace Teguh Rijanandi\ApiResponse\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Teguh Rijanandi\ApiResponse\ApiResponse
 */
class ApiResponse extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Teguh Rijanandi\ApiResponse\ApiResponse::class;
    }
}
