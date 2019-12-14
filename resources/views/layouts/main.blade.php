@extends('layouts.base')

@php
    $title = setting('title');
    $description = setting('description');
    $author = setting('author');
@endphp

@section('title')
    @hasSection('subtitle')
        @yield('subtitle') -
    @endif
    {{ $title }}
@endsection

@section('head')
    @if ($description)
        <meta name="description" content="{{ $description }}">
    @endif
    @if ($author)
        <meta name="author" content="{{ $author }}">
    @endif
    @section('og')
        <meta property="og:title" content="{{ $title }}">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ route('home') }}">
        <meta property="og:image" content="{{ asset('images/og-website.png') }}">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:description" content="{{ $description ?: $title }}">
    @show
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
@endsection

@prepend('stylesheets')
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
@endprepend

@prepend('javascripts')
    <script>
        window.PUSHER_APP_KEY = '{{ config('broadcasting.connections.pusher.key') }}';

        window.Translations = {
            error: {
                whoops: '{{ __('messages.error.whoops') }}',
                wrong: '{{ __('messages.error.wrong') }}'
            }
        };
    </script>
    <script src="{{ mix('js/manifest.js') }}"></script>
    <script src="{{ mix('js/vendor.js') }}"></script>
    <script src="{{ mix('js/app.js') }}"></script>
@endprepend
