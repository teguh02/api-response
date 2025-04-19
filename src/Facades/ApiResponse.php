<?php

namespace teguh02\ApiResponse\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \teguh02\ApiResponse\ApiResponse
 */
class ApiResponse extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'api-response';
    }
}
