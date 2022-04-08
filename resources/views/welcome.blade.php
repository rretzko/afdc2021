<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            background-image: url('../assets/images/backgrounds/cobblestones_small.jpg');
            background-size: cover;
            color: #fff;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        a
        {
            color: aliceblue;
            text-decoration: none;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {

            display: flex;
            justify-content: space-around;
            position: absolute;
            left: 0;
            top: 18px;
            width: 100%;
        }

        button {
            border-radius: 1rem;
        }

        .call-to-action {
            margin: 0 .5rem;
        }

        .content {
            text-align: center;
        }

        .demo, .invitation, .mission {
            background-color: rgba(0, 0, 0, .5);
            font-size: 1.6rem;
            padding: .5rem;
        }

        .demo {
            background-color: transparent;
            font-size: 1rem;
        }

        .invitation {
            font-size: 1.2rem;
        }

        .mission b{
            white-space: nowrap;
        }

        .title {
            color: yellow;
            font-size: 36px;
            font-weight: bold;
        }

        .links > a {
            color: #fff;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        @media screen and (min-device-width: 568px) {
            .title {
                position: relative;
                top: 24px;
            }
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ url('/home') }}">Home</a>
            @else
                <a href="{{ route('demo') }}" style="display: none;">Demo</a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}">Register</a>
                @endif

                <a href="{{ route('login') }}">Login</a>
            @endauth
        </div>
    @endif

    <div class="content">
        <div class="title m-b-md ">
            {{ config('app.name') }}
        </div>

        <div class="call-to-action">
            <div class="mission">
                AuditionForms.com is an event management system designed for <b>Teachers of Music.</b>
            </div>

            <div class="invitation">
                If you build your auditioned events using spreadsheets, docs, or any other system
                which is paper-and-time-intensive, we invite you to take a look!
            </div>

    </div>
</div>
</body>
</html>
