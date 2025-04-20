<?php

use teguh02\ApiResponse\ApiResponseBuilder;

if (!function_exists('to_json')) {
    /**
     * Convert data to JSON response
     *
     * @param mixed $data
     * @return void
     */
    function to_json(mixed $data) {
        return app(ApiResponseBuilder::class)
            ->with($data)
            ->build();
    }
}

if (!function_exists('format_json')) {
    /**
     * Format data to JSON response
     *
     * @param mixed $data
     * @return void
     */
    function format_json(mixed $data) {
        return app(ApiResponseBuilder::class)->with($data);
    }
}