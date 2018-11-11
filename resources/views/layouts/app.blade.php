<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
    <script>
        window.App = {!! json_encode([
            'signedIn' => Auth::check(),
            'user'     => Auth::user(),
        ]) !!};
    </script>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/trix/1.0.0/trix.css">

    <style>
        body { margin-bottom: 100px; }
        .level { display: flex; align-items: center; }
        .flex { flex: 1; }
        .card { margin-top:  15px; margin-bottom:  15px; }
        .mr-1 { margin-right: 1em !important; }
        [v-cloak] { display: none; }
        .ais-highlight > em { background-color: yellow; font-style: normal; }
    </style>

    @yield('header')
</head>
<body>
    <div id="app">
        @include('layouts.nav')

        <main class="py-4">
            @yield('content')
        </main>

        <flash message="{{ session('flash') }}"></flash>
    </div>

    <script src="/vendor/fontawesome/fontawesome-all.min.js"></script>

    @yield('scripts')
</body>
</html>
