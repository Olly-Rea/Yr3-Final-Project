@extends("layouts.app")

@section("styles")
<link href="{{ asset('css/access_page.css') }}" rel="stylesheet">
@endsection

@section("title")Register @endsection

@section('nav')
<div id="nav-left">
    <a href="/" id="site-logo">
        <svg>
            <use xlink:href="{{ asset('images/graphics/logo.svg#icon') }}"></use>
        </svg>
        <h3>Home</h3>
    </a>
</div>
<div id="site-links">
    <a @if(Request::is('CookBook'))class="active"@else href="{{ route('feed') }}"@endif><b>Home</b></a>
    <a @if(Request::is('register'))class="active" @else href="{{ route('register') }}"@endif><b>Sign Up!</b></a>
    <a @if(Request::is('login'))class="active"@else href="{{ route('login') }}"@endif><b>Login</b></a>
</div>
@endsection

@section("content")
    <div id="register-form"  class="content-panel">
        <h1>Start your culinary journey here!</h1>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
            @if ($errors->has('name'))<p class="form-error-msg">{{ $errors->first('name') }}</p> @endif
            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
            @if ($errors->has('email'))<p class="form-error-msg">{{ $errors->first('email') }}</p> @endif
            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
            @if ($errors->has('password'))<p class="form-error-msg">{{ $errors->first('password') }}</p> @endif
            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>
            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
            <button type="submit" class="btn btn-primary">
                {{ __('Register') }}
            </button>
        </form>
        <a href="{{ route('login') }}">Already have an account? Login here!</a>
    </div>
@endsection
