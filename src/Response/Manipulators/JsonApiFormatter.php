<?php

namespace teguh02\ApiResponse\Response\Manipulators;

class JsonApiFormatter 
{
    public static function make(array $data, string $formatterClass) 
    {
        foreach ($data as $k => $v) {
            $rules = app($formatterClass)->format($v);

            dd($rules);
        }

        return $data;
    }
}