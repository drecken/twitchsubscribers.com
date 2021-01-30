<?php

namespace App\Sorts;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class SubscriberNameSort implements Sort
{
    public function __invoke(Builder $query, $descending, string $property): Builder
    {
        return $query
            ->leftJoin('users as subscriber', 'subscriptions.subscriber_id', '=', 'subscriber.id')
            ->orderBy('subscriber.name', $descending ? 'desc' : 'asc');
    }
}

