<?php

namespace teguh02\ApiResponse\Response\Manipulators;

use Illuminate\Support\Arr;

class JsonApiTransformer 
{
    /**
     * Transform the given data for the API response.
     *
     * @param array $data
     * @param string $transformerClass
     * @return void
     */
    public static function make(array $data, string $transformerClass) 
    {
        $response = [];

        foreach ($data as $k => $v) {
            $rules = app($transformerClass)->transform($v);

            foreach (array_keys($rules) as $value) {
                // Check if key is exists in the original data
                // if not exists, create new key 
                // and assign the value from the rules
                if (!Arr::exists($v, $value)) {
                    $response[$k][$value] = $rules[$value];
                } else {
                    // if exists, assign the value from the original data
                    $response[$k][$value] = $v[$value];
                }
            }
        }

        return $response;
    }
}