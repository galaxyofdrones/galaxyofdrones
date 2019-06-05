@extends('layouts.main')

@section('body')
    <section id="app" class="container-app">
        @include('partials.sidebar')
        @include('partials.player')
        @include('partials.monitor')

        @yield('content')

        @include('partials.mailbox')
        @include('partials.message')
        @include('partials.mothership')
        @include('partials.profile')
        @include('partials.setting')
        @include('partials.trophy')
    </section>
@endsection
