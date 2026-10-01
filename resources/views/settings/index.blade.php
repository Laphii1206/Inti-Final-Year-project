@extends('layouts.app')

@section('styles')
    @include('partials.account-dark')
@endsection

@section('breadcrumb')
    <x-breadcrumb :items="[__('account.breadcrumb_home') => route('home'), __('account.breadcrumb_settings') => null]" />
@endsection

@section('content')
<div class="container my-3 my-md-5 acct-dark">
    @include('partials.mobile-account-tabs')
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4 order-2 order-md-1">
            @include('partials.customer-sidebar')
        </div>

        <!-- Main Content -->
        <div class="col-12 col-md-9 order-1 order-md-2">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4 text-start">
                    <h4 class="fw-bold mb-0"><i class="fa-solid fa-gear text-brand me-2"></i>{{ __('account.settings_title') ?? 'Settings' }}</h4>
                    <p class="text-muted small">{{ __('account.settings_desc') }}</p>
                </div>
                <div class="card-body p-4 text-start">
                    @if (session('success'))
                        <div class="alert alert-success border-0 bg-success text-white small mb-4">
                            {{ session('success') }}
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

                    <form action="{{ route('settings.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Preferences / Language -->
                        <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="fa-solid fa-globe text-brand me-2"></i>{{ __('account.settings_pref_title') ?? 'Preferences' }}</h6>
                        @php
                            $currentLang = old('language', auth()->user()->language ?? 'en');
                            $languagesList = [
                                'en' => [
                                    'name' => __('account.settings_lang_en') ?? 'English',
                                    'native' => 'English',
                                    'region' => 'Global / United States & UK',
                                    'flag' => '<svg viewBox="0 0 36 24" width="36" height="24" class="rounded-1 shadow-sm flex-shrink-0"><rect width="36" height="24" fill="#B22234"/><path d="M0,3.69H36 M0,11.07H36 M0,18.46H36" stroke="#FFFFFF" stroke-width="3.69"/><rect width="18" height="13" fill="#3C3B6E"/><g fill="#FFFFFF"><circle cx="3" cy="2.5" r="0.8"/><circle cx="7" cy="2.5" r="0.8"/><circle cx="11" cy="2.5" r="0.8"/><circle cx="15" cy="2.5" r="0.8"/><circle cx="5" cy="4.5" r="0.8"/><circle cx="9" cy="4.5" r="0.8"/><circle cx="13" cy="4.5" r="0.8"/><circle cx="3" cy="6.5" r="0.8"/><circle cx="7" cy="6.5" r="0.8"/><circle cx="11" cy="6.5" r="0.8"/><circle cx="15" cy="6.5" r="0.8"/><circle cx="5" cy="8.5" r="0.8"/><circle cx="9" cy="8.5" r="0.8"/><circle cx="13" cy="8.5" r="0.8"/><circle cx="3" cy="10.5" r="0.8"/><circle cx="7" cy="10.5" r="0.8"/><circle cx="11" cy="10.5" r="0.8"/><circle cx="15" cy="10.5" r="0.8"/></g></svg>'
                                ],
                                'ms' => [
                                    'name' => __('account.settings_lang_ms') ?? 'Bahasa Melayu',
                                    'native' => 'Bahasa Melayu',
                                    'region' => 'Malaysia / Nusantara',
                                    'flag' => '<svg viewBox="0 0 36 24" width="36" height="24" class="rounded-1 shadow-sm flex-shrink-0"><rect width="36" height="24" fill="#CC0000"/><g fill="#FFFFFF"><rect y="1.71" width="36" height="1.71"/><rect y="5.14" width="36" height="1.71"/><rect y="8.57" width="36" height="1.71"/><rect y="12.0" width="36" height="1.71"/><rect y="15.43" width="36" height="1.71"/><rect y="18.86" width="36" height="1.71"/><rect y="22.29" width="36" height="1.71"/></g><rect width="18" height="13.7" fill="#010066"/><path d="M8.5,3.5 A3.5,3.5 0 1,0 8.5,10.2 A2.8,2.8 0 1,1 8.5,3.5 Z" fill="#FFCC00"/><path d="M12.5,6.85 L10.8,6.85 L11.8,5.5 L10.3,6.2 L10.8,4.5 L9.6,5.6 L9.5,3.8 L8.6,5.1 L8.0,3.5 L7.6,5.1 L6.5,4.0 L6.8,5.5 L5.4,5.0 L6.2,6.2 L4.6,6.2 L5.8,7.1 L4.3,7.6 L5.8,8.2 L4.6,9.0 L6.2,9.0 L5.4,10.2 L6.8,9.7 L6.5,11.2 L7.6,10.1 L8.0,11.7 L8.6,10.1 L9.5,11.4 L9.6,9.6 L10.8,10.7 L10.3,9.0 L11.8,9.7 L10.8,8.4 L12.5,8.4 L11.2,7.6 Z" fill="#FFCC00"/></svg>'
                                ],
                                'zh' => [
                                    'name' => __('account.settings_lang_zh') ?? 'Chinese (Simplified)',
                                    'native' => '简体中文',
                                    'region' => 'Mainland China / Singapore',
                                    'flag' => '<svg viewBox="0 0 36 24" width="36" height="24" class="rounded-1 shadow-sm flex-shrink-0"><rect width="36" height="24" fill="#EE1C25"/><polygon points="6,3 7.2,6.5 10.8,6.5 7.9,8.6 9,12.1 6,10 3,12.1 4.1,8.6 1.2,6.5 4.8,6.5" fill="#FFFF00"/><polygon points="12,2 12.5,3 13.7,3 12.8,3.7 13.1,4.8 12,4.1 10.9,4.8 11.2,3.7 10.3,3 11.5,3" fill="#FFFF00" transform="rotate(15 12 3.4)"/><polygon points="14.5,4.5 15,5.5 16.2,5.5 15.3,6.2 15.6,7.3 14.5,6.6 13.4,7.3 13.7,6.2 12.8,5.5 14,5.5" fill="#FFFF00" transform="rotate(35 14.5 5.9)"/><polygon points="14.5,8.5 15,9.5 16.2,9.5 15.3,10.2 15.6,11.3 14.5,10.6 13.4,11.3 13.7,10.2 12.8,9.5 14,9.5" fill="#FFFF00"/><polygon points="12,11 12.5,12 13.7,12 12.8,12.7 13.1,13.8 12,13.1 10.9,13.8 11.2,12.7 10.3,12 11.5,12" fill="#FFFF00" transform="rotate(-15 12 12.4)"/></svg>'
                                ],
                                'ta' => [
                                    'name' => __('account.settings_lang_ta') ?? 'Tamil',
                                    'native' => 'தமிழ்',
                                    'region' => 'India / Malaysia / Singapore',
                                    'flag' => '<svg viewBox="0 0 36 24" width="36" height="24" class="rounded-1 shadow-sm flex-shrink-0"><rect width="36" height="8" fill="#FF9933"/><rect y="8" width="36" height="8" fill="#FFFFFF"/><rect y="16" width="36" height="8" fill="#138808"/><circle cx="18" cy="12" r="3.2" fill="none" stroke="#000080" stroke-width="0.7"/><circle cx="18" cy="12" r="0.6" fill="#000080"/><g stroke="#000080" stroke-width="0.4"><line x1="18" y1="8.8" x2="18" y2="15.2"/><line x1="14.8" y1="12" x2="21.2" y2="12"/><line x1="15.7" y1="9.7" x2="20.3" y2="14.3"/><line x1="20.3" y1="9.7" x2="15.7" y2="14.3"/><line x1="16.4" y1="9" x2="19.6" y2="15"/><line x1="19.6" y1="9" x2="16.4" y2="15"/><line x1="15" y1="10.4" x2="21" y2="13.6"/><line x1="21" y1="10.4" x2="15" y2="13.6"/></g></svg>'
                                ]
                            ];
                            $activeLang = $languagesList[$currentLang] ?? $languagesList['en'];
                        @endphp
                        <div class="row mb-4">
                            <div class="col-md-8 col-lg-7 mb-3">
                                <label class="form-label small fw-bold text-secondary mb-2">{{ __('account.settings_label_lang') ?? 'Language' }}</label>
                                <div class="position-relative w-100" id="customLangContainer">
                                    <!-- Hidden input for form submission -->
                                    <input type="hidden" name="language" id="languageInput" value="{{ $currentLang }}">

                                    <!-- Trigger Button -->
                                    <button type="button" 
                                            class="custom-lang-btn w-100 d-flex align-items-center justify-content-between text-start rounded-4" 
                                            id="langDropdownTrigger" 
                                            data-bs-toggle="dropdown" 
                                            aria-expanded="false">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="lang-flag-wrapper d-flex align-items-center justify-content-center" id="activeFlagDisplay">
                                                {!! $activeLang['flag'] !!}
                                            </div>
                                            <div>
                                                <div class="d-flex align-items-center gap-2">
                                                    <span class="fw-bold fs-6 text-white" id="activeNameDisplay">{{ $activeLang['native'] }}</span>
                                                    @if($activeLang['native'] !== $activeLang['name'])
                                                    <span class="badge bg-secondary-subtle small px-2 py-1 rounded-pill" id="activeTransDisplay">{{ $activeLang['name'] }}</span>
                                                    @else
                                                    <span class="badge bg-secondary-subtle small px-2 py-1 rounded-pill" id="activeTransDisplay" style="display:none;"></span>
                                                    @endif
                                                </div>
                                                <span class="small text-muted d-block mt-1" style="font-size: 0.8rem;" id="activeRegionDisplay">{{ $activeLang['region'] }}</span>
                                            </div>
                                        </div>
                                        <div class="lang-chevron d-flex align-items-center justify-content-center rounded-circle ms-2 flex-shrink-0">
                                            <i class="fa-solid fa-chevron-down small"></i>
                                        </div>
                                    </button>

                                    <!-- Dropdown Menu -->
                                    <div class="dropdown-menu custom-lang-menu w-100 p-2 shadow-lg border-0 rounded-4" aria-labelledby="langDropdownTrigger">
                                        <div class="px-3 py-2 mb-1 d-flex align-items-center justify-content-between border-bottom border-secondary border-opacity-25">
                                            <span class="small text-muted fw-semibold text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.8px;">
                                                <i class="fa-solid fa-earth-americas text-brand me-1"></i> Select Display Language
                                            </span>
                                            <span class="badge bg-danger-subtle text-danger small">4 Available</span>
                                        </div>
                                        
                                        <div class="d-flex flex-column gap-1 mt-1">
                                            @foreach($languagesList as $code => $lang)
                                            <div class="lang-option-card d-flex align-items-center justify-content-between p-3 rounded-3 {{ $currentLang === $code ? 'active' : '' }}"
                                                 data-value="{{ $code }}"
                                                 data-native="{{ $lang['native'] }}"
                                                 data-name="{{ $lang['name'] }}"
                                                 data-region="{{ $lang['region'] }}"
                                                 role="button"
                                                 tabindex="0">
                                                <div class="d-flex align-items-center gap-3">
                                                    <div class="lang-flag-wrapper d-flex align-items-center justify-content-center">
                                                        {!! $lang['flag'] !!}
                                                    </div>
                                                    <div>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <span class="fw-bold text-white fs-6">{{ $lang['native'] }}</span>
                                                            @if($lang['native'] !== $lang['name'])
                                                            <span class="badge bg-secondary-subtle small px-2 py-0.5 rounded-pill">{{ $lang['name'] }}</span>
                                                            @endif
                                                        </div>
                                                        <span class="small text-muted d-block mt-0.5" style="font-size: 0.8rem;">{{ $lang['region'] }}</span>
                                                    </div>
                                                </div>
                                                <div class="lang-status-indicator d-flex align-items-center justify-content-center rounded-circle flex-shrink-0">
                                                    @if($currentLang === $code)
                                                    <i class="fa-solid fa-check fs-6"></i>
                                                    @else
                                                    <div class="lang-empty-circle rounded-circle"></div>
                                                    @endif
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Password Change -->
                        <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="fa-solid fa-lock text-brand me-2"></i>{{ __('account.settings_pwd_title') ?? 'Change Password' }}</h6>
                        <p class="small text-muted mb-3">{{ __('account.settings_pwd_desc') ?? 'Leave blank if you do not want to change your password.' }}</p>
                        <div class="row mb-4">
                            <div class="col-md-12 mb-3">
                                <label class="form-label small fw-bold text-secondary">{{ __('account.settings_label_current_pwd') ?? 'Current Password' }}</label>
                                <input type="password" name="current_password" class="form-control bg-light border-0" placeholder="Enter your current password to confirm">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary">{{ __('account.settings_label_new_pwd') ?? 'New Password' }}</label>
                                <input type="password" name="password" class="form-control bg-light border-0" placeholder="At least 8 characters">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold text-secondary">{{ __('account.settings_label_confirm_pwd') ?? 'Confirm New Password' }}</label>
                                <input type="password" name="password_confirmation" class="form-control bg-light border-0" placeholder="Repeat new password">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-brand px-4 py-2 fw-bold rounded-3">Save Settings</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- WebAuthn: Passkey Registration -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 text-start">
                    <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="fa-solid fa-shield-halved text-brand me-2"></i>Security & Passkeys</h6>
                    @include('partials.webauthn-register')
                </div>
            </div>

            <!-- Danger Zone: Account Deletion -->
            @if(auth()->check() && auth()->user()->isCustomer())
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 text-start">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div>
                            <h6 class="fw-bold text-danger mb-1"><i class="fa-solid fa-user-xmark me-2"></i>{{ __('account.del_title') ?? 'Delete Account' }}</h6>
                            <p class="small text-muted mb-0">{{ __('account.del_desc') ?? 'Request permanent deletion of your account and associated data.' }}</p>
                        </div>
                        <div>
                            @if(isset($deleteRequest) && $deleteRequest)
                                <button class="btn btn-secondary px-4 small" disabled>
                                    <i class="fa-solid fa-hourglass-start me-1"></i> {{ __('account.del_pending') ?? 'Deletion Pending' }}
                                </button>
                            @else
                                <button class="btn btn-outline-danger px-4 small fw-semibold" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                    <i class="fa-solid fa-trash-can me-1"></i> {{ __('account.del_btn') ?? 'Request Deletion' }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal: Delete Account Request -->
            <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow rounded-4">
                        <div class="modal-header bg-danger text-white border-0 py-3">
                            <h5 class="modal-title fw-bold" id="deleteAccountModalLabel"><i class="fa-solid fa-triangle-exclamation me-2"></i>{{ __('account.del_modal_title') ?? 'Confirm Deletion Request' }}</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form action="{{ route('delete-request.store') }}" method="POST">
                            @csrf
                            <div class="modal-body p-4 bg-white text-start">
                                <div class="alert alert-warning border-0 bg-warning-subtle text-warning-emphasis small mb-3">
                                    <i class="fa-solid fa-circle-exclamation me-2"></i><strong>Warning:</strong> {{ __('account.del_warning_body') ?? 'Account deletion is irreversible. All bookings, cars, and voucher records will be erased upon approval.' }}
                                </div>
                                <div class="mb-3">
                                    <label for="reason" class="form-label small fw-bold text-secondary">{{ __('account.del_reason_label') ?? 'Reason for leaving (Optional)' }}</label>
                                    <textarea class="form-control" id="reason" name="reason" rows="3" placeholder="Tell us why you are requesting account deletion..."></textarea>
                                </div>
                            </div>
                            <div class="modal-footer border-0 p-3">
                                <button type="button" class="btn btn-outline-secondary small" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-danger px-4 small fw-bold">Submit Request</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const langCards = document.querySelectorAll('.lang-option-card');
    const hiddenInput = document.getElementById('languageInput');
    const activeFlagDisplay = document.getElementById('activeFlagDisplay');
    const activeNameDisplay = document.getElementById('activeNameDisplay');
    const activeRegionDisplay = document.getElementById('activeRegionDisplay');
    const activeTransDisplay = document.getElementById('activeTransDisplay');

    langCards.forEach(card => {
        card.addEventListener('click', function() {
            // Remove active class from all
            langCards.forEach(c => {
                c.classList.remove('active');
                const indicator = c.querySelector('.lang-status-indicator');
                if (indicator) {
                    indicator.innerHTML = '<div class="lang-empty-circle rounded-circle"></div>';
                }
            });

            // Add active class to clicked card
            this.classList.add('active');
            const indicator = this.querySelector('.lang-status-indicator');
            if (indicator) {
                indicator.innerHTML = '<i class="fa-solid fa-check fs-6"></i>';
            }

            // Update hidden input value
            const val = this.getAttribute('data-value');
            if (hiddenInput) {
                hiddenInput.value = val;
            }

            // Update trigger button displays
            const flagWrapper = this.querySelector('.lang-flag-wrapper');
            if (flagWrapper && activeFlagDisplay) {
                activeFlagDisplay.innerHTML = flagWrapper.innerHTML;
            }
            const nativeName = this.getAttribute('data-native');
            const localizedName = this.getAttribute('data-name');
            const region = this.getAttribute('data-region');

            if (activeNameDisplay) activeNameDisplay.textContent = nativeName;
            if (activeRegionDisplay) activeRegionDisplay.textContent = region;

            if (activeTransDisplay) {
                if (nativeName !== localizedName) {
                    activeTransDisplay.textContent = localizedName;
                    activeTransDisplay.style.display = 'inline-block';
                } else {
                    activeTransDisplay.style.display = 'none';
                }
            }

            // Close dropdown smoothly
            const btn = document.getElementById('langDropdownTrigger');
            if (btn && typeof bootstrap !== 'undefined') {
                const bsDropdown = bootstrap.Dropdown.getInstance(btn) || new bootstrap.Dropdown(btn);
                if (bsDropdown) {
                    bsDropdown.hide();
                }
            }
        });
    });
});
</script>
@endsection
