<?php

namespace teguh02\ApiResponse;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use teguh02\ApiResponse\Contracts\ApiFormatterInterface;
use teguh02\ApiResponse\Contracts\ApiTransformerInterface;
use teguh02\ApiResponse\Contracts\ApiValidatorInterface;
use Illuminate\Support\Facades\Log;
use teguh02\ApiResponse\Response\ArrayApiResponse;
use teguh02\ApiResponse\Response\BuilderApiResponse;
use teguh02\ApiResponse\Response\CollectionApiResponse;

class ApiResponseBuilder
{
    protected $data;
    protected $transformer;
    protected $formatter;
    protected $validator;
    protected $statusCode = 200;
    protected $headers = [];
    protected $meta = [];

    public function with(array|Collection|Builder $data)
    {
        $this->data = $data;
        return $this;
    }

    public function transformWith(ApiTransformerInterface $transformer)
    {
        $this->transformer = $transformer;
        return $this;
    }

    public function formatWith(ApiFormatterInterface $formatter)
    {
        $this->formatter = $formatter;
        return $this;
    }

    public function validateWith(ApiValidatorInterface $validator)
    {
        $this->validator = $validator;
        return $this;
    }

    public function withStatusCode(int $statusCode = 200)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function withHeaders(array $headers = [])
    {
        $this->headers = array_merge(config('api-response.headers', []), $headers);
        return $this;
    }

    public function withMeta(array $meta = [])
    {
        $this->meta = config('api-response.api.display_meta') 
            ? array_merge(config('api-response.meta', []), $meta) 
            : $meta;

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

    public function build()
    {
        Log::info('Building API response', [
            'data' => $this->data,
            'data_type' => gettype($this->data),
            'transformer' => $this->transformer,
            'formatter' => $this->formatter,
            'validator' => $this->validator,
            'statusCode' => $this->statusCode,
            'headers' => $this->headers,
            'meta' => $this->meta,
        ]);

        $response = [
            config('api-response.api.response.data_key') => match (true) {
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
            },

            config('api-response.api.response.status_code_key') => $this->statusCode,
            'meta' => $this->meta,
        ];

        return response() -> json($response);
    }
}