@extends('layouts.main')

@section('content')
    <style>
        h4{
            color: #6441A4;
        }
        .twitch-glitch{
            height: 75px;
            transform: rotate(180deg);
        }
    </style>
    <h2><img src="{{ asset('twitch.png') }}" alt="Twitch Glitch" class="twitch-glitch" /> Oh no!</h2>

    <h4>The Twitch API returned error <code>422</code> and said:</h4>
    <blockquote>
        <h5>{{ $exception->getMessage() }}</h5>
    </blockquote>

    <h6><a href='/'>Go back home</a></h6>
@endsection