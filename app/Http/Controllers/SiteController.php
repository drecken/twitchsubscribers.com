<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class SiteController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return $this->guest();
        }
        $user = Auth::user()->load('subscriptions');

        return view('index', compact('user'));
    }

    public function guest()
    {
        return view('guest');
    }

    public function user()
    {
        return response()->json(Auth::user()->load('subscriptions'));
    }

}
