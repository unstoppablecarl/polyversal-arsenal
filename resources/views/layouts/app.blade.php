<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Polyversal Arsenal</title>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body>
<div id="app">
    @include('layouts.partials.navigation')

    <div class="container">
        @yield('breadcrumbs')

        @include('layouts.partials.errors')
        @include('layouts.partials.message')
    </div>

    <div class="app-wrapper {{$app_container_class OR 'container' }}">
        @yield('content')
    </div>
    @yield('content-after')
</div>

@include('layouts.partials.footer')


<script>
    var APP_DATA = {!! isset($JS_DATA) ? json_encode($JS_DATA) : '{}'  !!};
</script>

@yield('pre-js')

<script src="{{mix('/js/manifest.js')}}"></script>
<script src="{{mix('/js/vendor.js')}}"></script>
<script src="{{mix('/js/app.js')}}"></script>

@yield('js')
@stack('js')
</body>
</html>
