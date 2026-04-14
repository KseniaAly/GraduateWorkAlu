<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <link rel="stylesheet" href="{{asset('/css/bootstrap.min.css')}}">
    <script src="{{asset('/js/bootstrap.bundle.js')}}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{asset('/assets/styles/style.css')}}">

{{--    @vite(['resources/css/app.css', 'resources/js/app.js'])--}}
</head>
<body style="background: linear-gradient(rgba(179,218,255,0.5), #F2F5FD); width: 100%; min-height: 100vh; background-attachment: fixed"
class="d-flex flex-column w-100 align-items-center">
{{--    <div id="app"></div>--}}
    <div style="width: 90%; margin-top: 40px">
        @include('layout.navbar')
        @yield('content')
    </div>
</body>
</html>
