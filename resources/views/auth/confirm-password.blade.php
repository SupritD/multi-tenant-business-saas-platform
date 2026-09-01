@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        {{ __('Confirm Password') }}
                    </div>

                    <div class="card-body">
                        <p>
                            {{ __('Please confirm your password before continuing.') }}
                        </p>

                        <form method="POST" action="{{ route('password.confirm') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    {{ __('Password') }}
                                </label>

                                <input id="password" type="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" required
                                    autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary">
                                {{ __('Confirm Password') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection