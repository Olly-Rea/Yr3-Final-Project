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

        <button type="submit" class="btn btn-primary">{{ __('Reset Password') }}</button>
    </form>
</div>
<a href="{{ url()->previous() }}" id="back-btn">Back</a>
@endsection
