<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('dist/img/logo2.png') }}">
    <title>E-Legalisir | @yield('title')</title>

    @include('template.partials._style')
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
    <div class="wrapper">
        @include('template.partials._navbar')

        @include('template.partials._sidebar')

        @yield('content')

        @include('template.partials._footer')
    </div>
    <!-- ./wrapper -->

    @include('template.partials._script')
</body>

</html>
