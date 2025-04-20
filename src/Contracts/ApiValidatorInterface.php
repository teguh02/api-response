<?php

namespace teguh02\ApiResponse\Contracts;

interface ApiValidatorInterface
{
    /**
     * Validate the given data for the API response.
     *
     * @param array $data
     * @return array
     */
    public function validate() : array;
}