<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{config('settings.system_title')}}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #575e62;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
            /* background-image: url("https://unsplash.com/photos/Tzm3Oyu_6sk/download?ixid=M3wxMjA3fDB8MXxzZWFyY2h8Nnx8cHJpbnRpbmclMjBjb3Jwb3JhdGlvbnxlbnwwfHx8fDE3MTQ1OTE2Nzd8MA&auto=format&fit=crop&w=1521&q=80"); */
            background-image: url("https://unsplash.com/photos/ZCTh4f4mv18/download?ixid=M3wxMjA3fDB8MXxzZWFyY2h8NXx8cHJpbnRpbmclMjBjb3Jwb3JhdGlvbnxlbnwwfHx8fDE3MTQ1OTE2Nzd8MA&auto=format&fit=crop&w=1521&q=80");
            background-repeat: no-repeat;
            background-size: cover;
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
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .text-light{
            color:white;
        }
        .links > a {
            color: white;
            padding: 5px 20px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
            border: 1px solid white;
            border-radius: 10px;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    @if (Route::has('login'))
        <div class="top-right links">
            @auth
                <a href="{{ route('admin.dashboard') }}">Home</a>
            @else
                <a href="{{ route('login') }}" class="text-light">Login</a>
                <a href="{{ route('register') }}" class="text-light">Register</a>

            @endauth
        </div>
    @endif

    <div class="content text-light">
        <div class="title m-b-md">
            {{config('settings.system_title')}}
        </div>

        <div class="links">
            <blockquote>
                {{$quotes}}
            </blockquote>
        </div>
    </div>
</div>
</body>
</html>
