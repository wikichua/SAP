@extends('sap::layouts.guest')

@section('content')
<div class="p-5">
    <div class="text-center">
        <h1 class="h4 text-gray-900 mb-2">Confirm Password</h1>
        <p class="mb-4">{{ __('Please confirm your password before continuing.') }}</p>
    </div>
    <form class="user" method="POST" action="{{ route('password.confirm') }}">
        @csrf
        @honeypot
        <div class="form-group">
            <input type="password" class="form-control form-control-user @error('password') is-invalid @enderror"
                name="password" required autocomplete="new-password" placeholder="Password">
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary btn-user btn-block">
            {{ __('Confirm Password') }}
        </button>
    </form>
    <hr>
    <div class="text-center">
        <a class="small" href="{{ route('password.request') }}">Forgot Password</a>
    </div>
</div>

@endsection
