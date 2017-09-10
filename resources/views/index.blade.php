@extends('layouts.main')

@section('content')
    <div class="center-align">
        <h1>Want to view you Twitch subscribers list?</h1>
        <a class="waves-effect waves-light btn-large" href="{{ route('twitch') }}">Show me my Twitch Subscribers</a>
    </div>
@endsection