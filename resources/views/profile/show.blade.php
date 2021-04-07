@extends('layouts.nav')

@section("styles")
<link href="{{ asset('css/profile_page.css') }}" rel="stylesheet">
@endsection

@section('title')
{{ config('app.name', 'Laravel') }}
@endsection

@section('content')

<div class="profile-image-container">
    <div class="profile-image">
        <img src="{{ $user->profileImage() }}" alt="{{ $user->first_name }} {{ $user->last_name }}">
    </div>
</div>

<h1>{{ $user->first_name }} {{ $user->last_name }}</h1>

<div id="user-recipes">
@foreach ($user->recipes as $recipe)

    <div>{{ $recipe->name }}</div>

@endforeach
</div>

<div id="user-ratings">
@foreach ($user->ratings as $rating)

@endforeach
</div>

{{-- Show User 'fridge' (if active User's profile) --}}
@if(Request::is('/Me'))
<div id="user-fridge">

</div>
@endif

@endsection

{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updateProfileInformation()))
                @livewire('profile.update-profile-information-form')

                <x-jet-section-border />
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-jet-section-border />
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-jet-section-border />
            @endif

            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            <x-jet-section-border />

            <div class="mt-10 sm:mt-0">
                @livewire('profile.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout> --}}
