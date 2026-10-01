{{-- WebAuthn: Register Passkey Partial --}}
{{-- Include this in any authenticated view to let users register Face ID / Fingerprint --}}

<div class="webauthn-btn" style="display: none;">
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-dark text-white rounded-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fa-solid fa-fingerprint fs-4"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold mb-0 text-dark">{{ __('booking.wa_title') }}</h6>
                        <p class="small text-muted mb-0">
                            @if(auth()->user()->webAuthnCredentials()->count() > 0)
                                <span class="text-success"><i class="fa-solid fa-circle-check me-1"></i>{{ auth()->user()->webAuthnCredentials()->count() }} {{ __('booking.wa_registered') }}</span>
                            @else
                                {{ __('booking.wa_register_prompt') }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if(auth()->user()->webAuthnCredentials()->count() > 0)
                        <form action="{{ route('webauthn.destroy') }}" method="POST" class="m-0" onsubmit="return confirm('{{ __('booking.wa_remove_confirm') }}');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm fw-bold px-3 py-2 rounded-3">
                                <i class="fa-solid fa-trash-can me-1"></i> {{ __('booking.wa_btn_remove') }}
                            </button>
                        </form>
                    @endif
                    <button type="button" class="btn btn-brand btn-sm fw-bold px-3 py-2 rounded-3" id="webauthn-register-btn">
                        <i class="fa-solid fa-plus me-1"></i> {{ __('booking.wa_btn_register') }}
                    </button>
                </div>
            </div>

            <!-- Status Alert -->
            <div class="alert d-none small mt-3 mb-0 border-0 rounded-3 py-2 text-center" id="webauthn-register-alert" role="alert"></div>
        </div>
    </div>
</div>

{{-- WebAuthn unsupported fallback (hidden when supported) --}}
<div class="webauthn-unsupported" style="display: none;">
    {{-- Intentionally empty — don't show anything if unsupported --}}
</div>

@push('scripts')
<script src="{{ asset('js/webauthn.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const registerBtn = document.getElementById('webauthn-register-btn');
        const alertEl = document.getElementById('webauthn-register-alert');

        if (registerBtn) {
            registerBtn.addEventListener('click', async function() {
                registerBtn.disabled = true;
                registerBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin me-1"></i> {{ __('booking.wa_registering') }}';

                const success = await WebAuthnHelper.registerPasskey(function(type, message) {
                    alertEl.classList.remove('d-none');
                    if (type === 'info') {
                        alertEl.className = 'alert small mt-3 mb-0 border-0 rounded-3 py-2 text-center bg-info bg-opacity-10 text-info';
                    } else if (type === 'success') {
                        alertEl.className = 'alert small mt-3 mb-0 border-0 rounded-3 py-2 text-center bg-success text-white';
                    } else {
                        alertEl.className = 'alert small mt-3 mb-0 border-0 rounded-3 py-2 text-center bg-danger text-white';
                    }
                    alertEl.innerHTML = '<i class="fa-solid fa-' + (type === 'success' ? 'circle-check' : type === 'error' ? 'circle-xmark' : 'spinner fa-spin') + ' me-1"></i> ' + message;
                });

                registerBtn.disabled = false;
                registerBtn.innerHTML = '<i class="fa-solid fa-plus me-1"></i> {{ __('booking.wa_btn_register') }}';

                if (success) {
                    // Reload after 2 seconds to update the credential count
                    setTimeout(() => window.location.reload(), 2000);
                }
            });

            @if(session('auto_prompt_webauthn'))
            // Auto-trigger registration since they opted in during account creation
            setTimeout(() => {
                if (document.querySelector('.webauthn-btn').style.display !== 'none') {
                    registerBtn.click();
                }
            }, 500);
            @endif
        }
    });
</script>
@endpush
