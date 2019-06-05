@extends('layouts.main')

@php
    $subtitle = trans('messages.start');
@endphp

@section('subtitle', $subtitle)

@section('body')
    <section id="app" class="container-app container-app-standalone">
        <a class="logo" href="{{ route('home') }}">
            <img class="img-fluid"
                 src="{{ mix('images/logo.png') }}"
                 alt="{{ setting('title') }}">
        </a>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-globe-americas"></i>
                    {{ $subtitle }}
                </h5>
            </div>
            @if ($count)
                <form action="{{ route('start') }}" method="post">
                    {{ csrf_field() }}

                    <div class="card-body text-center">
                        <span class="item planet-1"></span>
                        <h5>
                            {{ trans_choice('messages.planet.free', $count) }}
                        </h5>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary btn-lg btn-block" type="submit">
                            {{ trans('messages.planet.first') }}
                        </button>
                    </div>
                </form>
            @else
                <div class="card-body text-center">
                    <h5>
                        {{ trans('messages.warning.server') }}
                    </h5>
                </div>
            @endif
        </div>
    </section>
@endsection
