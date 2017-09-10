@extends('layouts.main')

@section('content')
    <h1>You have {{ number_format(count($subscribers), 0) }} subscribers</h1>
    <table class="highlight">
        @foreach($subscribers as $subscriber)
            <tr>
                <td>
                    {{ $loop->iteration }}
                </td>
                <td>
                    @if($subscriber['logo'])
                        <img src="{{ $subscriber['logo'] }}" width="50px">
                    @else
                        <img src="https://static-cdn.jtvnw.net/jtv_user_pictures/xarth/404_user_70x70.png" width="50px">
                    @endif
                </td>
                <td>
                    {{ $subscriber['name'] }} (<a
                            href="https://www.twitch.tv/{{ $subscriber['displayName'] }}">{{ $subscriber['displayName'] }}</a>
                    )
                </td>
                <td>
                    Tier {{ $subscriber['tier'] }}
                </td>
            </tr>
        @endforeach
    </table>
@endsection