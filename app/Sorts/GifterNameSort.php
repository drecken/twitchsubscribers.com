<?php

namespace App\Sorts;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class GifterNameSort implements Sort
{
    public function __invoke(Builder $query, $descending, string $property): Builder
    {
        return $query
            ->leftJoin('users as gifter', 'subscriptions.gifter_id', '=', 'gifter.id')
            ->orderBy('gifter.name', $descending ? 'desc' : 'asc');
    }
}

