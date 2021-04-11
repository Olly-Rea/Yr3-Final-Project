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
    <div id="login-form" class="content-panel">
        <h1>Welcome back!</h1>
        <form  method="POST" action="{{ route('login') }}">
            @csrf
            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
            @if ($errors->has('email'))<p class="form-error-msg">{{ $errors->first('email') }}</p> @endif
            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
            @if ($errors->has('password'))<p class="form-error-msg">{{ $errors->first('password') }}</p> @endif
            @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
            @endif
            <label class="checkOption">
                <input type="checkbox" name="remember" checked>
                <span class="checkbox">
                    <svg>
                        <use xlink:href="{{ asset('images/graphics/checkbox.svg#icon') }}"></use>
                    </svg>
                    Remember Me
                </span>
            </label>
            <button type="submit" class="btn btn-primary">
                {{ __('Login') }}
            </button>
        </form>
        <a href="{{ route('register') }}">Don't have an account? Sign up here!</a>
    </div>
@endsection
