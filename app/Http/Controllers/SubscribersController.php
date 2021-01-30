<?php

namespace App\Http\Controllers;

use App\Jobs\SynchronizeSubscriptions;
use App\Models\Subscription;
use App\Sorts\GifterNameSort;
use App\Sorts\SubscriberNameSort;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class SubscribersController extends Controller
{
    public function fetch()
    {
        return QueryBuilder::for(Subscription::class)
            ->allowedIncludes(['subscriber', 'gifter'])
            ->allowedFilters(
                [
                    'tier',
                    'subscriber.name',
                    'gifter.name',
                ]
            )
            ->allowedSorts(
                [
                    'tier',
                    AllowedSort::custom('subscriber.name', new  SubscriberNameSort()),
                    AllowedSort::custom('gifter.name', new  GifterNameSort()),
                ]
            )
            ->jsonPaginate();
    }

    public function synchronize(Request $request)
    {
        $cooldown = config('app.synchronize_cooldown_seconds');
        if (Carbon::now()->diffInSeconds($request->user()->twitch_synced_at) > $cooldown) {
            $request->user()->update(['twitch_synced_at' => now()]);
            SynchronizeSubscriptions::dispatch(Auth::user());
            return response(['twitch_synced_at' => $request->user()->twitch_synced_at], 200);
        }
        return response(
            [
                'errors' => [
                    [
                        'status' => 403,
                        'title' => 'Action not ready yet',
                        'detail' => 'Must wait 24 hours between syncs.',
                    ],
                ],
            ],
            403
        );
    }
}
