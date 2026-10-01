@extends('layouts.app')

@section('styles')
    @include('partials.account-dark')
@endsection

@section('content')
<div class="container my-5 py-5 acct-dark">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="bg-dark text-white text-center py-4 position-relative">
                    <h3 class="fw-bold mb-0">{{ __('auth.reset_title') }}</h3>
                    <p class="small text-white-50 mb-0">{{ __('auth.reset_desc') }}</p>
                </div>
                <div class="card-body p-5 bg-white">
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 bg-danger text-white small mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        
                        <div class="mb-4">
                            <label for="email" class="form-label small fw-bold text-secondary">{{ __('auth.reset_label_email') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                                <input type="email" name="email" id="email" class="form-control bg-light border-0 py-2.5" placeholder="name@example.com" value="{{ $email ?? old('email') }}" required readonly>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label small fw-bold text-secondary">{{ __('auth.reset_label_pwd') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fa-solid fa-lock text-muted"></i></span>
                                <input type="password" name="password" id="password" class="form-control bg-light border-0 py-2.5" placeholder="••••••••" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label small fw-bold text-secondary">{{ __('auth.reset_label_confirm') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fa-solid fa-lock text-muted"></i></span>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control bg-light border-0 py-2.5" placeholder="••••••••" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-brand w-full py-2.5 fw-bold rounded-3 mb-3"><i class="fa-solid fa-check me-2"></i>{{ __('auth.reset_btn') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
