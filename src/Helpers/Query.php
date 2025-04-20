<?php

namespace teguh02\ApiResponse\Helpers;

use Illuminate\Database\Eloquent\Builder;

class Query 
{
    /**
     * Combines SQL and its bindings
     *
     * @param \Eloquent $query
     * @return string
     */
    public static function toSql(Builder $query)
    {
        return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
            return is_numeric($binding) ? $binding : "'{$binding}'";
        })->toArray());
    }
}