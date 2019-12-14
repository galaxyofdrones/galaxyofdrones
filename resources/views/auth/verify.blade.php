@extends('layouts.main')

@php
    $subtitle = __('verification.verify');
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
                    <i class="fas fa-envelope-open-text"></i>
                    {{ $subtitle }}
                </h5>
            </div>
            <div class="card-body">
                {{ __('verification.check') }}
                {{ __('verification.not_receive') }},

                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                    @csrf

                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline">
                        {{ __('verification.resend') }}
                    </button>.
                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-user-edit"></i>
                    {{ __('messages.email.update') }}
                </h5>
            </div>
            <form action="{{ route('verification.update') }}" method="post">
                {{ csrf_field() }}

                <div class="card-body">
                    <div class="form-group mb-0">
                        <label class="required" for="email">
                            {{ __('validation.attributes.email') }}
                        </label>
                        <input id="email"
                               class="form-control form-control-lg {{ $errors->has('email') ? 'is-invalid' : '' }}"
                               type="email"
                               name="email"
                               value="{{ old('email', auth()->user()->email) }}"
                               placeholder="{{ __('validation.attributes.email') }}" required>
                        @if ($errors->has('email'))
                            <span class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">
                        {{ __('messages.save') }}
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('javascripts')
    @include('partials.flash')
@endpush
