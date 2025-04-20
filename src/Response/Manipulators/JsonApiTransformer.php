<?php

namespace teguh02\ApiResponse\Response\Manipulators;

class JsonApiTransformer 
{
    /**
     * Transform the given data using the specified transformer class.
     *
     * @param array $data
     * @param string $transformerClass
     * @return void
     */
    public static function make(array $data, string $transformerClass) 
    {
        return array_map(function ($item) use ($transformerClass) {
            $rules = app($transformerClass)->transform($item);
            return array_intersect_key($item, array_flip(array_keys($rules)));
        }, $data);
    }
}