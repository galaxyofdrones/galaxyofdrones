@extends('layouts.main')

@php
    $subtitle = trans('messages.auth.login');
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
                    <i class="fas fa-sign-in-alt"></i>
                    {{ $subtitle }}
                </h5>
            </div>
            <form action="{{ route('login') }}" method="post">
                {{ csrf_field() }}

                <div class="card-body">
                    <div class="form-group">
                        <label class="required" for="username_or_email">
                            {{ trans('validation.attributes.username_or_email') }}
                        </label>
                        <input id="username_or_email"
                               class="form-control form-control-lg {{ $errors->has('username_or_email') ? 'is-invalid' : '' }}"
                               type="text"
                               name="username_or_email"
                               value="{{ old('username_or_email') }}"
                               placeholder="{{ trans('validation.attributes.username_or_email') }}" required>
                        @if ($errors->has('username_or_email'))
                            <span class="invalid-feedback">
                                {{ $errors->first('username_or_email') }}
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="required" for="password">
                            {{ trans('validation.attributes.password') }}
                        </label>
                        <input id="password"
                               class="form-control form-control-lg {{ $errors->has('password') ? 'is-invalid' : '' }}"
                               type="password"
                               name="password"
                               placeholder="{{ trans('validation.attributes.password') }}" required>
                        @if ($errors->has('password'))
                            <span class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </span>
                        @endif
                    </div>
                    <div class="form-check">
                        <label class="form-check-label">
                            <input class="form-check-input"
                                   type="checkbox"
                                   name="remember" checked>
                            {{ trans('validation.attributes.remember') }}
                        </label>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">
                        {{ $subtitle }}
                    </button>
                </div>
                <div class="card-footer text-center">
                    <p class="mb-1">
                        <a href="{{ route('password.request') }}">
                            {{ trans('messages.auth.forgot') }}
                        </a>
                    </p>
                    <p class="mb-0">
                        {{ trans('messages.auth.dont_have') }}
                        <a href="{{ route('register') }}">
                            {{ trans('messages.auth.register') }}
                        </a>
                    </p>
                </div>
            </form>
        </div>
    </section>
@endsection

@push('javascripts')
    @include('partials.flash')
@endpush
