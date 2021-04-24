@extends('layouts.nav')

@section("styles")
<link href="{{ asset('css/errors.css') }}" rel="stylesheet">
@endsection

@section('content')
<div id="message">
    <h1>@yield('code')</h1>
    <div></div>
    <h3>@yield('message')</h3>
</div>
@endsection
