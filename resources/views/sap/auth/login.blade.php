@extends('sap::layouts.guest')

@section('content')
<div class="p-5">
    <div class="text-center">
        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
    </div>
    <form method="POST" class="user" action="{{ $loginUrl ?? route('login')  }}">
        @csrf
        <div class="form-group">
            <input id="email" type="email" class="form-control form-control-user @error('email') is-invalid @enderror"
                placeholder="Enter Email Address..." name="email" value="{{ old('email') }}" required
                autocomplete="email" autofocus>
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <input id="password" type="password"
                class="form-control form-control-user @error('password') is-invalid @enderror" name="password" required
                autocomplete="current-password" placeholder="Password">

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <div class="form-group">
            <div class="custom-control custom-checkbox small">
                <input class="custom-control-input" type="checkbox" name="remember" id="remember"
                    {{ old('remember') ? 'checked' : '' }}>
                <label class="custom-control-label" for="remember">
                    {{ __('Remember Me') }}
                </label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-user btn-block">
            {{ __('Login') }}
        </button>
    </form>
    @if (preg_match('/'.(config('sap.custom_pub_path')).'.*/', request()->route()->getName()))
    <hr>
        @foreach(array_keys(config('services')) as $provider)
            @if (config("services.{$provider}.client_secret",'') != '')
    <div class="text-center">
        <a class="btn btn-outline-dark btn-user btn-block" href="{{ route('pub.social.login', [$provider]) }}">
            <i class="fab fa-{{ $provider }} mr-2"></i> Login with {{ ucwords($provider) }}</a>
    </div>
            @endif
        @endforeach
    @endif
    <hr>
    <div class="text-center">
        @if(Route::has('password.request'))
            <a class="small" href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
        @endif
    </div>
    <div class="text-center">
        @if(Route::has('register'))
            <a class="small" href="{{ route('register') }}">
                Create An Account
            </a>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>

</script>
@endpush
