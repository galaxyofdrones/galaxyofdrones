<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8" />
        <title>@yield('title')</title>
        @yield('head')
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @stack('stylesheets')
        <link rel="icon" type="image/x-icon" href="{{ mix('favicon.ico') }}" />
    </head>
    <body>
        @yield('body')
        @stack('javascripts')
    </body>
</html>
