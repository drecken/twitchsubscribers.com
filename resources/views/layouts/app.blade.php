<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Twitch Subscribers</title>
    <meta name="description" content="View your twitch subscribers">
    <meta name="author" content="drecken">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/5.8.55/css/materialdesignicons.min.css">
</head>
<body>
<div id="app">
    <div class="container">
        <b-navbar>
            <template #brand>
                <b-navbar-item tag="a" href="{{ route('index') }}" class="navbar-item brand-text is-size-1">
                    Twitch Subscribers
                </b-navbar-item>
            </template>
            <template #end>
                <b-navbar-item tag="div">
                    <div class="buttons">
                        <b-button
                            tag="a"
                            href="https://github.com/drecken/twitchsubscribers.com"
                            target="_blank"
                            type="is-primary is-light"
                            icon-left="github"
                        >
                            GitHub
                        </b-button>
                        @auth
                            <a href="{{ route('logout') }}" class="button is-primary">
                                Log out
                            </a>
                        @endauth
                        @guest
                            <a href="{{ route('login-twitch') }}" class="button is-primary">
                                <span>Log in with Twitch</span>
                                <span class="icon is-small"><i class="mdi mdi-twitch"></i></span>
                            </a>
                        @endguest
                    </div>
                </b-navbar-item>
            </template>
        </b-navbar>
    </div>
    @yield('content')
</div>
<script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
