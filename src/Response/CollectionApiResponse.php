<?php

namespace teguh02\ApiResponse\Response;

use Illuminate\Support\Collection;
use teguh02\ApiResponse\Contracts\ApiFormatterInterface;
use teguh02\ApiResponse\Contracts\ApiTransformerInterface;
use teguh02\ApiResponse\Contracts\ApiValidatorInterface;

class CollectionApiResponse
{
    protected Collection $data;
    protected ?ApiTransformerInterface $transformer = null;
    protected ?ApiFormatterInterface $formatter = null;
    protected ?ApiValidatorInterface $validator = null;

    public function __construct(
        Collection $data,
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
        return [];
    }
}