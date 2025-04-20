<?php

namespace teguh02\ApiResponse\Contracts;

interface ApiTransformerInterface
{
    /**
     * Transform the given data for the API response.
     *
     * @param array $data
     * @return array
     */
    public function transform(array $data) : array;
}