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
                    <h3 class="fw-bold mb-0"><span class="text-brand">TRB</span> Auto Car Care</h3>
                    <p class="small text-white-50 mb-0">{{ __('auth.login_title') }}</p>
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

                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="login" class="form-label small fw-bold text-secondary">{{ __('auth.login_label_email') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fa-solid fa-user text-muted"></i></span>
                                <input type="text" name="login" id="login" class="form-control bg-light border-0 py-2.5" placeholder="{{ __('auth.login_ph_email') }}" value="{{ old('login') }}" required autofocus>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label small fw-bold text-secondary">{{ __('auth.login_label_pwd') }}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fa-solid fa-lock text-muted"></i></span>
                                <input type="password" name="password" id="password" class="form-control bg-light border-0 py-2.5" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="mb-4 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label small text-secondary" for="remember">
                                    {{ __('auth.login_remember') }}
                                </label>
                            </div>
                            <a href="{{ route('password.request') }}" class="small text-brand fw-bold text-decoration-none">{{ __('auth.login_forgot') }}</a>
                        </div>

                        <button type="submit" class="btn btn-brand w-full py-2.5 fw-bold rounded-3 mb-3"><i class="fa-solid fa-right-to-bracket me-2"></i>{{ __('auth.login_btn') }}</button>
                        <div class="d-flex align-items-center my-4">
                            <hr class="flex-grow-1 border-secondary-subtle my-0">
                            <span class="px-3 small text-muted fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.08em; text-transform: uppercase;">{{ __('auth.login_or') }}</span>
                            <hr class="flex-grow-1 border-secondary-subtle my-0">
                        </div>

                        <div class="d-flex flex-column gap-3 mb-4">
                            <a href="{{ route('auth.google') }}" class="btn-sso">
                                <svg class="sso-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48">
                                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                                </svg>
                                <span>{{ __('auth.login_google') }}</span>
                            </a>
                            <a href="{{ route('auth.facebook') }}" class="btn-sso">
                                <svg class="sso-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24">
                                    <path fill="#1877F2" d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    <path fill="#FFF" d="M16.671 15.543l.532-3.47h-3.328v-2.25c0-.949.465-1.874 1.956-1.874h1.513V5.049s-1.374-.235-2.686-.235c-2.741 0-4.533 1.662-4.533 4.669v2.59H7.078v3.47h3.047v8.385a12.09 12.09 0 001.875.146c.638 0 1.264-.052 1.875-.146v-8.385h2.796z"/>
                                </svg>
                                <span>{{ __('auth.login_facebook') }}</span>
                            </a>
                        </div>

                        <div class="text-center">
                            <p class="small text-muted mb-0">{{ __('auth.login_no_account') }} <a href="{{ route('register') }}" class="text-brand fw-bold text-decoration-none">{{ __('auth.login_register_link') }}</a></p>
                        </div>
                    </form>

                    <!-- WebAuthn: Face ID / Fingerprint Login -->
                    <div class="webauthn-btn" style="display: none;">
                        <div class="d-flex align-items-center my-4">
                            <hr class="flex-grow-1 border-secondary-subtle">
                            <span class="px-3 small text-muted fw-bold">{{ __('auth.login_or') }}</span>
                            <hr class="flex-grow-1 border-secondary-subtle">
                        </div>

                        <!-- Status Alert -->
                        <div class="alert d-none small mb-3 border-0 rounded-3 py-2 text-center" id="webauthn-login-alert" role="alert"></div>

                        <button type="button" class="btn btn-dark w-full py-2.5 fw-bold rounded-3 d-flex align-items-center justify-content-center gap-2" id="webauthn-login-btn">
                            <i class="fa-solid fa-fingerprint fs-5"></i>
                            {{ __('auth.login_webauthn') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('js/webauthn.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginBtn = document.getElementById('webauthn-login-btn');
        const alertEl = document.getElementById('webauthn-login-alert');

        if (loginBtn) {
            loginBtn.addEventListener('click', async function() {
                loginBtn.disabled = true;
                loginBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-2"></i> ' + '{{ __("auth.login_verifying") }}';

                await WebAuthnHelper.loginWithPasskey(function(type, message) {
                    alertEl.classList.remove('d-none', 'bg-info', 'bg-success', 'bg-danger', 'text-white');
                    if (type === 'info') {
                        alertEl.className = 'alert small mb-3 border-0 rounded-3 py-2 text-center bg-info bg-opacity-10 text-info';
                    } else if (type === 'success') {
                        alertEl.className = 'alert small mb-3 border-0 rounded-3 py-2 text-center bg-success text-white';
                    } else {
                        alertEl.className = 'alert small mb-3 border-0 rounded-3 py-2 text-center bg-danger text-white';
                    }
                    alertEl.innerHTML = '<i class="fa-solid fa-' + (type === 'success' ? 'circle-check' : type === 'error' ? 'circle-xmark' : 'spinner fa-spin') + ' me-1"></i> ' + message;
                });

                loginBtn.disabled = false;
                loginBtn.innerHTML = '<i class="fa-solid fa-fingerprint fs-5 me-2"></i> ' + '{{ __("auth.login_webauthn") }}';
            });
        }
    });
</script>
@endsection

