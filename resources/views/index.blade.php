@extends('layouts.main')

@section('content')
    <div class="center-align">
        <h1>Want to view you Twitch subscribers list?</h1>
        <a class="waves-effect waves-light btn-large" href="{{ route('twitch.authorize') }}">Show me my Twitch
            Subscribers</a>
        <p>
            In order to see your subscribers you need to authorize this website to access that information. This is an
            open source project available <a href="https://github.com/drecken/twitchsubscribers.com">here</a>.
        </p>
    </div>
@endsection