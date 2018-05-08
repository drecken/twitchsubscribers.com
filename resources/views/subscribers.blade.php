@extends('layouts.main')

@section('content')
<style>
    .user-info {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        /* height: 50px; */
    }
    .user-info > div{
        padding: 5px;
    }
</style>
<h1>You have {{ number_format(count($subscribers), 0) }} {{ str_plural('subscriber', count($subscribers)) }}</h1>
<table class="highlight">
    <thead>
        <tr>
            <th>#</th>
            <th>Sub Date</th>
            <th>User</th>
            <th>Sub Level</th>
        </tr>
    </thead>
    @foreach($subscribers as $subscriber)
    <tr>
        <td>
            {{ $loop->iteration }}
        </td>
        <td>
            <span class="sub-date">{{ $subscriber['date'] }}</span>
        </td>
        <td>
            <div class="user-info">
                <div>
                    @if($subscriber['logo'])
                    <img src="{{ $subscriber['logo'] }}" height="50px">
                    @else
                    <img src="https://static-cdn.jtvnw.net/jtv_user_pictures/xarth/404_user_70x70.png" height="50px">
                    @endif
                </div>
                <div>
                    {{ $subscriber['name'] }} (<a href="https://www.twitch.tv/{{ $subscriber['displayName'] }}">{{ $subscriber['displayName'] }}</a>)
                </div>
            </div>
        </td>
        <td>
            @if($subscriber['tier'] == 'Prime')
            Prime                
            @else
            Tier {{ $subscriber['tier'] }}
            @endif
        </td>
    </tr>
    @endforeach
</table>

<script src="/js/date_fns.min.js"></script>
<script>
    $(document).ready(function () {
        $(".sub-date").each(function(v){
            $(this).html(dateFns.format(new Date($(this).html().trim()), "MMM Do, YYYY @ h:mm a"));
        });
        
    });
</script>
@endsection