<?php

namespace teguh02\ApiResponse\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \teguh02\ApiResponse\Response\ApiResponseBuilder with($data)
 * @method static \teguh02\ApiResponse\Response\ApiResponseBuilder append(string $key, mixed $value = null)
 * @method static \teguh02\ApiResponse\Response\ApiResponseBuilder transformWith($transformer = null)
 * @method static \teguh02\ApiResponse\Response\ApiResponseBuilder formatWith($formatter = null)
 * @method static \teguh02\ApiResponse\Response\ApiResponseBuilder validateWith($validator = null)
 * @method static \teguh02\ApiResponse\Response\ApiResponseBuilder withStatusCode(int $statusCode = null)
 * @method static \teguh02\ApiResponse\Response\ApiResponseBuilder withHeaders(array $headers = [])
 * @method static \teguh02\ApiResponse\Response\ApiResponseBuilder withMeta(array $meta = [])
 * @method static \teguh02\ApiResponse\Response\ApiResponseBuilder notFound()
 * @method static \teguh02\ApiResponse\Response\ApiResponseBuilder success()
 * @method static \teguh02\ApiResponse\Response\ApiResponseBuilder created()
 * @method static \teguh02\ApiResponse\Response\ApiResponseBuilder badRequest()
 * @method static \teguh02\ApiResponse\Response\ApiResponseBuilder unauthorized()
 * @method static \teguh02\ApiResponse\Response\ApiResponseBuilder forbidden()
 * @method static \teguh02\ApiResponse\Response\ApiResponseBuilder internalServerError()
 * @method static \teguh02\ApiResponse\Response\ApiResponseBuilder notAcceptable()
 * @method static \teguh02\ApiResponse\Response\ApiResponseBuilder notAllowed()
 * @method static \teguh02\ApiResponse\Response\ApiResponseBuilder serviceUnavailable()
 * @method static \teguh02\ApiResponse\Response\ApiResponseBuilder build()
 *
 * @see \teguh02\ApiResponse\Response\ApiResponseBuilder
 */
class ApiResponse extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'api-response';
    }
}
