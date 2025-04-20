<?php

namespace teguh02\ApiResponse\Contracts;

interface ApiFormatterInterface
{
    /**
     * Format the given data for the API response.
     *
     * @return array
     */
    public function format() : array;
}