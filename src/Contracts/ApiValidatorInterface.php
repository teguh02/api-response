<?php

namespace teguh02\ApiResponse\Contracts;

interface ApiValidatorInterface
{
    /**
     * Validate the given data for the API response.
     *
     * @param array $data
     * @return void
     */
    public function validate(array $data);
}