<?php

namespace teguh02\ApiResponse\Response\Manipulators;

class JsonApiTransformer 
{
    public static function make(array $data, string $transformerClass) 
    {
        return $data;
    }
}