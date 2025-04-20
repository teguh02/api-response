<?php

namespace teguh02\ApiResponse;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use teguh02\ApiResponse\Contracts\ApiFormatterInterface;
use teguh02\ApiResponse\Contracts\ApiTransformerInterface;
use teguh02\ApiResponse\Contracts\ApiValidatorInterface;
use Illuminate\Support\Facades\Log;
use teguh02\ApiResponse\Helpers\Query;
use teguh02\ApiResponse\Response\ArrayApiResponse;
use teguh02\ApiResponse\Response\BuilderApiResponse;
use teguh02\ApiResponse\Response\CollectionApiResponse;

class ApiResponseBuilder
{
    protected array|Collection|Builder|string|int|float $data;
    protected ?ApiTransformerInterface $transformer = null;
    protected ?ApiFormatterInterface $formatter = null;
    protected ?ApiValidatorInterface $validator = null;
    protected int $statusCode = 200;
    protected ?array $headers = [];
    protected ?array $meta = [];
    protected ?array $debug = [];
    protected ?array $custom_appends_attributes = [];

    public function with(
        array|Collection|Builder|string|int|float $data
    )
    {
        if (is_string($data) or is_int($data) or is_float($data)) {
            $data = [$data];
        }

        $this->data = $data;
        $this->debug['data'] = [
            'type' => gettype($data),
            'query' => match (true) {
                $data instanceof Builder => Query::toSql($data),
                $data instanceof Collection => $data->toArray(),
                default => $data,
            },
            'class' => match (true) {
                $data instanceof Builder => get_class($data),
                $data instanceof Collection => get_class($data),
                default => gettype($data),
            },
        ];

        return $this;
    }

    public function transformWith(?ApiTransformerInterface $transformer = null)
    {
        $this->transformer = $transformer;
        $this->debug['transformer'] = $transformer::class;
        return $this;
    }

    public function formatWith(?ApiFormatterInterface $formatter = null)
    {
        $this->formatter = $formatter;
        $this->debug['formatter'] = $formatter::class;
        return $this;
    }

    public function validateWith(?ApiValidatorInterface $validator = null)
    {
        $this->validator = $validator;
        $this->debug['validator'] = $validator::class;
        return $this;
    }

    public function withStatusCode(int $statusCode = 200)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function withHeaders(array $headers = [])
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    public function withMeta(array $meta = [])
    {
        $this->meta = array_merge($this->meta, $meta);
        return $this;
    }

    public function append(string $key, mixed $value = null)
    {
        $this->custom_appends_attributes[$key] = $value;
        return $this;
    }

    #️⃣ Defined Default status codes
    public function notFound() { $this->statusCode = 404; return $this; }
    public function success() { $this->statusCode = 200; return $this;}
    public function created() { $this->statusCode = 201; return $this; }
    public function badRequest() { $this->statusCode = 400; return $this; }
    public function unauthorized() { $this->statusCode = 401; return $this; }
    public function forbidden() { $this->statusCode = 403; return $this; }
    public function internalServerError() { $this->statusCode = 500; return $this; }
    public function notAcceptable() { $this->statusCode = 406; return $this; }
    public function notAllowed() { $this->statusCode = 405; return $this; }
    public function serviceUnavailable() { $this->statusCode = 503; return $this; }

    public function build()
    {
        $response = [];

        # Set default headers and meta
        $this->headers = array_merge(config('api-response.headers', []));
        $this->meta = config('api-response.api.display_meta') 
            ? array_merge(config('api-response.meta', [])) 
            : [];

        # For Debugging purposes
        if (config('api-response.api.debug')) {
            $this->debug['status_code'] = $this->statusCode;
            $this->debug['headers'] = $this->headers;
            $this->debug['meta'] = $this->meta;
            $this->debug['appends'] = $this->custom_appends_attributes;
            $this->debug['info']  = "This _debug are shown because the config 'api-response.api.debug' is set to true";            
            Log::debug('[teguh02/api-response] API response debug', $this->debug);
        }

        # Get the data
        $data = match (true) {
            $this->data instanceof Builder => new BuilderApiResponse(
                data: $this->data,
                transformer: $this->transformer,
                formatter: $this->formatter,
                validator: $this->validator,
            ),
            $this->data instanceof Collection => new CollectionApiResponse(
                data: $this->data,
                transformer: $this->transformer,
                formatter: $this->formatter,
                validator: $this->validator,
            ),
            default => new ArrayApiResponse(
                data: $this->data,
                transformer: $this->transformer,
                formatter: $this->formatter,
                validator: $this->validator,
            ),
        };
        $data = $data->build();

        $response[config('api-response.api.response.data_key')] = $data['data'];

        // Append custom attributes to response array
        if (filled($this->custom_appends_attributes)) {
            $response = array_merge($response, $this->custom_appends_attributes);
        }

        if (config('api-response.api.pagination.enabled')) {
            $response['pagination'] = [
                'total' => $data['total'],
                'per_page' => $data['per_page'],
                'current_page' => $data['current_page'],
                'last_page' => $data['last_page'],
                'from' => $data['from'],
                'to' => $data['to'],
                'url' => [
                    'first_page' => $data['first_page_url'],
                    'last_page' => $data['last_page_url'],
                    'next_page' => $data['next_page_url'],
                    'prev_page' => $data['prev_page_url'],
                ],
            ];   
        }

        if (config('api-response.api.display_status_code')) {
            $response[config('api-response.api.response.status_code_key')] = $this->statusCode;
        }

        if (config('api-response.api.display_meta')) {
            $response['meta'] = $this->meta;
        }

        if (config('api-response.api.debug')) {
            $response['_debug'] = $this->debug;
        }

        return response() -> json(
            data: $response,
            status: $this->statusCode,
            headers: $this->headers,
        );
    }
}