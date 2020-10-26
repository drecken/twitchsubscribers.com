<!doctype html>

<html lang="en">
<head>
    <meta charset="utf-8">

    <title>Twitch Subscribers</title>
    <meta name="description" content="Twitch Subscribers">
    <meta name="author" content="Drecken">

    {{--    <link rel="stylesheet" href="css/styles.css?v=1.0">--}}

</head>

<body>
@auth
    Welcome {{ \Illuminate\Support\Facades\Auth::user()->name }}
@endauth

@guest
    <a href="{{ route('login-twitch') }}">Log In</a>
@endguest
{{--<script src="js/scripts.js"></script>--}}
</body>
</html>
