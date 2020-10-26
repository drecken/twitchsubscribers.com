<?php

namespace App\Http\Controllers;

use App\Jobs\SynchronizeSubscriptions;
use Illuminate\Support\Facades\Auth;

class SubscribersController extends Controller
{
    public function index()
    {
        return view('subscribers.index');
    }

    public function fetch()
    {
    }

    public function sync()
    {
        SynchronizeSubscriptions::dispatch(Auth::user());
    }
}
