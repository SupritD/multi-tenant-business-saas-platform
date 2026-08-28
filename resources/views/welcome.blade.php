@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row align-items-stretch">
        <!-- Login Form -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">{{ __('Login to your Account') }}</h4>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        @if(app()->environment('local'))
                            @php $devUsers = \App\Models\User::orderBy('tenant_id')->orderBy('email')->get(); @endphp
                            <div class="mb-3 p-3 border border-danger rounded bg-light">
                                <label for="dev-user-select" class="form-label fw-bold text-danger">🛠️ Quick Dev Login</label>
                                <select id="dev-user-select" class="form-select border-danger">
                                    <option value="">-- Choose a user --</option>
                                    @foreach($devUsers as $u)
                                        <option value="{{ $u->email }}">{{ $u->name }} ({{ $u->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const devSelect = document.getElementById('dev-user-select');
                                    if(devSelect) {
                                        devSelect.addEventListener('change', function() {
                                            if(this.value) {
                                                document.getElementById('login-email').value = this.value;
                                                document.getElementById('login-password').value = 'Password@123';
                                            }
                                        });
                                    }
                                });
                            </script>
                        @endif

                        <div class="mb-3">
                            <label for="login-email" class="form-label fw-bold">{{ __('Email Address') }}</label>
                            <input id="login-email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="login-password" class="form-label fw-bold">{{ __('Password') }}</label>
                            <input id="login-password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                {{ __('Login') }}
                            </button>
                        </div>
                        
                        @if (Route::has('password.request'))
                            <div class="text-center mt-3">
                                <a class="text-decoration-none" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        <!-- Register Form -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow border-0">
                <div class="card-header bg-success text-white text-center py-3">
                    <h4 class="mb-0">{{ __('Create a New Account') }}</h4>
                </div>
                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="register-name" class="form-label fw-bold">{{ __('Name') }}</label>
                            <input id="register-name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name">
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="register-email" class="form-label fw-bold">{{ __('Email Address') }}</label>
                            <input id="register-email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="register-password" class="form-label fw-bold">{{ __('Password') }}</label>
                            <input id="register-password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password-confirm" class="form-label fw-bold">{{ __('Confirm Password') }}</label>
                            <input id="password-confirm" type="password" class="form-control form-control-lg" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
