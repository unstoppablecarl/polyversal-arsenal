<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Polyversal Arsenal</title>

    <link href="{{ asset('/build/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('/build/css/tile-front-svg.css') }}" rel="stylesheet" id="tile-front-svg-css">
    <link href="{{ asset('/build/css/tile-back-svg.css') }}" rel="stylesheet" id="tile-back-svg-css">

    @yield('head-end')
</head>
<?php
if (empty($bodyClass)) {
    $bodyClass = '';
}
?>
<body class="{{$bodyClass}}">

<div id="app" class="app">
    @include('layouts.partials.navigation')

    <div class="container container-breadcrumbs">
        @yield('breadcrumbs')

        @include('layouts.partials.errors')
        @include('layouts.partials.message')
    </div>

    <div class="app-wrapper container">
        @yield('content')
    </div>
    @yield('content-after')
</div>

@include('layouts.partials.footer')


<script>
    var APP_DATA = {!! isset($JS_DATA) ? json_encode($JS_DATA) : '{}'  !!};
</script>

@yield('pre-js')

<script src="{{mix('/build/js/manifest.js')}}"></script>
<script src="{{mix('/build/js/vendor.js')}}"></script>
<script src="{{mix('/build/js/app.js')}}"></script>

@yield('js')
@stack('js')
<div class="footer text-center">
    <h4>Polyversal Arsenal</h4>
    <h5>
        Created by
        <a href="https://github.com/unstoppablecarl">UnstoppableCarl</a>
    </h5>
    <p>

        <a href="https://polyversal-game.com/">Polyversal Site</a>
        -
        <a href="https://www.collinsepicwargames.com/privacy.html">Privacy Policy</a>
        -
        <a href="https://collinsepicwargames.com/sitepolicy.html">Site Policy</a>
        -
        <a href="https://github.com/unstoppablecarl/polyversal-arsenal">Github Project</a>

    </p>


    <p class="text-center">Polyversal Copyright &copy; 2019 Collins Epic Wargames, LLC. All Rights Reserved.</p>

</div>
</body>
</html>
