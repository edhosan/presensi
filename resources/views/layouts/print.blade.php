<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('admin_template/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('normalize.css/normalize.css') }}" rel="stylesheet">
    <link href="{{ asset('paper-css/paper.css') }}" rel="stylesheet">
    @stack('css')
    <script src="{{ asset('admin_template/js/bootstrap.js') }}"></script>
    <script type="text/javascript">
       window.onload = function() { window.print(); }
    </script>
</head>
@yield('content')
</html>
