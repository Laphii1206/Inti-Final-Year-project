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
                    <h3 class="fw-bold mb-0">{{ __('auth.forgot_title') }}</h3>
                    <p class="small text-white-50 mb-0">{{ __('auth.forgot_desc') }}</p>
                </div>
                <div class="card-body p-5 bg-white">
                    @if (session('status'))
                        <div class="alert alert-success border-0 bg-success text-white small mb-4">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger border-0 bg-danger text-white small mb-4">
                            <ul class="mb-0 ps-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="email" class="form-label small fw-bold text-secondary">{{ __('auth.forgot_label_email') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fa-solid fa-envelope text-muted"></i></span>
                                <input type="email" name="email" id="email" class="form-control bg-light border-0 py-2.5" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-brand w-full py-2.5 fw-bold rounded-3 mb-3"><i class="fa-solid fa-paper-plane me-2"></i>{{ __('auth.forgot_btn') }}</button>

                        <div class="text-center">
                            <p class="small text-muted mb-0">{{ __('auth.forgot_remembered') }} <a href="{{ route('login') }}" class="text-brand fw-bold text-decoration-none">{{ __('auth.forgot_login_link') }}</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
