<?php

namespace teguh02\ApiResponse\Response;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use teguh02\ApiResponse\Contracts\ApiFormatterInterface;
use teguh02\ApiResponse\Contracts\ApiTransformerInterface;
use teguh02\ApiResponse\Contracts\ApiValidatorInterface;
use teguh02\ApiResponse\Helpers\Pagination;

use teguh02\ApiResponse\Response\Manipulators\JsonApiFormatter;
use teguh02\ApiResponse\Response\Manipulators\JsonApiTransformer;
use teguh02\ApiResponse\Response\Manipulators\JsonApiValidator;

class BuilderApiResponse 
{
    protected Builder $data;
    protected ?ApiTransformerInterface $transformer = null;
    protected ?ApiFormatterInterface $formatter = null;
    protected ?ApiValidatorInterface $validator = null;

    public function __construct(
        Builder $data,
        $transformer = null,
        $formatter = null,
        $validator = null
    )
    {
        $this->data = $data;
        $this->transformer = $transformer;
        $this->formatter = $formatter;
        $this->validator = $validator;
    }

    public function build() : array
    {
        $data = $this->data->get();;

        foreach (['transformer' => JsonApiTransformer::class, 'formatter' => JsonApiFormatter::class, 'validator' => JsonApiValidator::class] as $property => $class) {
            if (filled($this->{$property})) {
                if (is_array($data)) {
                    $data = $class::make($data, $this->{$property}::class);
                } else {
                    $data = $class::make($data->toArray(), $this->{$property}::class);
                }
            }
        }

        // Check if $data was not collection
        if (!$data instanceof Collection) {
            $data = collect($data);
        }

        if (config('api-response.api.pagination.enabled')) {
            $data = Pagination::paginate($data, config('api-response.api.pagination.per_page'));

            return [
                'data' => $data->items(),
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
                'next_page_url' => $data->nextPageUrl(),
                'prev_page_url' => $data->previousPageUrl(),
                'first_page_url' => $data->url(1),
                'last_page_url' => $data->url($data->lastPage()),
            ];
        }

        return [
            'data' => $data,
            'total' => $data->count(),
            'per_page' => $data->count(),
            'current_page' => 1,
            'last_page' => 1,
            'from' => 1,
            'to' => $data->count(),
            'next_page_url' => null,
            'prev_page_url' => null,
            'first_page_url' => null,
            'last_page_url' => null,
        ];
    }
}