@extends("layouts.app")

@section("styles")
<link href="{{ asset('css/access_page.css') }}" rel="stylesheet">
@endsection

@section("title")Login @endsection

@section('nav')
<nav>
    <div id="nav-left">
        <a href="/" id="site-logo">
            <svg>
                <use xlink:href="{{ asset('images/graphics/logo.svg#icon') }}"></use>
            </svg>
        </a>
    </div>
    <div id="site-links">
        <a @if(Request::is('IdeasBoard'))class="active"@else href="{{ route('feed') }}"@endif><b>Home</b></a>
        <a @if(Request::is('register'))class="active" @else href="{{ route('register') }}"@endif><b>Sign Up!</b></a>
        <a @if(Request::is('login'))class="active"@else href="{{ route('login') }}"@endif><b>Login</b></a>
    </div>
</nav>
@endsection

@section("content")
<div id="help-form" class="content-panel">
    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <label for="email">{{ __('E-Mail Address') }}</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
        @error('email')<span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>@enderror
        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
        @if ($errors->has('password'))<p class="form-error-msg">{{ $errors->first('password') }}</p> @endif
        <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">

        <button type="submit" class="btn btn-primary">{{ __('Reset Password') }}</button>
    </form>
</div>
<a href="{{ url()->previous() }}" id="back-btn">Back</a>
@endsection


{{-- <x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="block">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus />
            </div>

            <div class="mt-4">
                <x-jet-label for="password" value="{{ __('Password') }}" />
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-jet-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
                <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-jet-button>
                    {{ __('Reset Password') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout> --}}
